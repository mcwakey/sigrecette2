<?php
namespace App\Http\Controllers;
use App\DataTables\ExportInvoicesDataTable;
use App\DataTables\ExportRecoveriesDataTable;
use App\DataTables\ExportTaxablesDataTable;
use App\DataTables\ExportTaxpayersDataTable;
use App\DataTables\ExportTaxpayerTaxablesDataTable;
use App\Enums\ExportTypeEnums;
use App\Exports\InvoiceExport;
use App\Helpers\Constants;
use App\Models\Activity;
use App\Models\BackupLog;
use App\Models\Canton;
use App\Models\Category;
use App\Models\TaxLabel;
use App\Models\Town;
use App\Models\Year;
use App\Models\Zone;
use App\Traits\HandlesDateFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    use  HandlesDateFilters;
    public function index(Request $request,
                          ExportTaxpayersDataTable $exportTaxpayersDataTable,
                          ExportInvoicesDataTable $exportInvoicesDataTable,
                          ExportRecoveriesDataTable $exportRecoveriesDataTable,
    ExportTaxpayerTaxablesDataTable $exportTaxpayerTaxablesDataTable,
    ExportTaxablesDataTable $exportTaxablesDataTable)
    {
        $this->handleDateFilters($request);

        $validatedData = $request->validate([
            'export_type' => ['string', Rule::in(array_keys(Constants::EXPORT_VALIDATION_MAP))],
            'disable' => ['nullable', 'integer', Rule::in(1)],
            'state' => ['nullable', 'string', Rule::in('at')],
        ]);
        $export_type = isset($validatedData['export_type']) ? Constants::EXPORT_VALIDATION_MAP[$validatedData['export_type']] : null;
        $tax_labels = TaxLabel::all();
        if ($export_type == ExportTypeEnums::TAXPAYER) {
            $disable = $validatedData['disable'] ?? null;
            $state = $validatedData['state'] ?? null;
            $zones = Zone::all();
            $categories = Category::all();
            $towns = Town::all();
            $cantons = Canton::all();
            $activities = Activity::all();
            $default_range = $this->getDefaultDateRange();
            //dd($this->s_date==$default_range['s_date']?null: $this->s_date);
            return $exportTaxpayersDataTable->with(
                [
                    'state' => $state,
                    'disable' => $disable,
                    'startDate' => $this->s_date==$default_range['s_date']?null: $this->s_date,
                    'endDate' => $this->e_date==$default_range['e_date']?null: $this->e_date,
                ]
            )->render('pages/export.taxpayers.list', ['zones' => $zones, 'categories' => $categories, 'towns' => $towns, 'cantons' => $cantons, 'activities' => $activities]);
        } elseif ($export_type == ExportTypeEnums::INVOICE) {
            $zones = Zone::all();
            return $exportInvoicesDataTable->with(
                [
                    'startDate' => $this->s_date,
                    'endDate' => $this->e_date,
                ]
            )->render('pages/export.invoices.list', ['zones' => $zones, 'tax_labels' => $tax_labels]);
        } elseif ($export_type == ExportTypeEnums::TAXPAYER_TAXABLE){
            return $exportTaxpayerTaxablesDataTable->with(
                [
                    'startDate' => $this->s_date,
                    'endDate' => $this->e_date,
                ]
            )->render('pages/export.taxpayer_taxables.list', []);
        }
        elseif ($export_type == ExportTypeEnums::TAXABLE){
            return $exportTaxablesDataTable->with(
                [
                    'startDate' => $this->s_date,
                    'endDate' => $this->e_date,
                ]
            )->render('pages/export.taxables.list', []);
        }
        else {
            return $exportRecoveriesDataTable->with(
                [
                    'startDate' => $this->s_date,
                    'endDate' => $this->e_date,
                ]
            )->render('pages/export.recoveries.list', ['tax_labels' => $tax_labels]);
        }
    }
    public function downloadExportInvoice(Request $request)
    {

        $invoiceIds = $request->input('ids', null);
        $this->handleDateFilters($request);

        return Excel::download(new InvoiceExport($this->s_date,$this->e_date,$invoiceIds), 'avis.xlsx');

    }
    public function backup()
    {
        return view('pages/export/backup.show',[
            'backup' => $this->getLastBackup()
        ]);
    }
    public function backupDownload(Request $request)
    {
        $request->merge([
            'created_at' => $request->created_at ? Carbon::parse($request->created_at)->format('Y-m-d H:i:s') : null,
        ]);

        $validatedData = $request->validate([
            'created_at' => 'nullable|date_format:Y-m-d H:i:s',
        ]);
        $last_date = $validatedData['created_at'];
        if (PHP_OS_FAMILY !== 'Linux') {
            return response()->json([
                'error' => 'Cette action est uniquement disponible sur un environnement Linux.'
            ], 403);
        }
        if($last_date==null){
            $diskName = config('backup.backup.destination.disks')[0];
            $rootPath = config('filesystems.disks.' . $diskName . '.root');

            $files = collect(Storage::disk($diskName)->files(config('app.name')))
                ->filter(fn($file) => Str::endsWith($file, '.zip'))
                ->sortByDesc(fn($file) => Storage::disk($diskName)->lastModified($file));

            $latestBackup = $files->first(fn($file) =>
                now()->diffInHours(
                    date('Y-m-d H:i:s', Storage::disk($diskName)->lastModified($file))
                ) < 2
            );

            if (!$latestBackup) {
                Artisan::call('backup:run');

                $latestBackup = collect(Storage::disk($diskName)->files(config('app.name')))
                    ->filter(fn($file) => Str::endsWith($file, '.zip'))
                    ->sortByDesc(fn($file) => Storage::disk($diskName)->lastModified($file))
                    ->first();
            }

            if (!$latestBackup) {
                return response()->json([
                    'error' => 'Aucune sauvegarde trouvée',
                    'disk_name' => $diskName,
                ], 404);
            }
            $file_name= basename($latestBackup);
            $fullPath = realpath($rootPath . DIRECTORY_SEPARATOR . $latestBackup);
        }else{
            $last=$this->getLastBackup();
            $file_name=$last['name'];
            $diskName=$last['disk_name'];
            if($last_date==$last['created_at']){
                $fullPath=$last['url'];
            }

        }
        if ($fullPath) {
            $this->saveBackupLog($file_name,$diskName,$fullPath);
            return response()->download($fullPath);
        } else {
            return response()->json(['error' => 'Fichier introuvable'], 404);
        }
    }
    public function getLastBackup()
    {
        $diskName = config('backup.backup.destination.disks')[0];
        $rootPath = config('filesystems.disks.' . $diskName . '.root');

        $file = collect(Storage::disk($diskName)->files(config('app.name')))
            ->filter(fn($file) => Str::endsWith($file, '.zip'))
            ->sortByDesc(fn($file) => Storage::disk($diskName)->lastModified($file))
            ->first();

        if (!$file) {
            return null;
        }

        $fullPath = realpath($rootPath . DIRECTORY_SEPARATOR . $file);
        $lastModified = Storage::disk($diskName)->lastModified($file);
        $formattedDate = date('Y-m-d H:i:s', $lastModified);

        return [
            'disk_name' => $diskName,
            'url' => $fullPath,
            'name' => basename($file),
            'created_at' => $formattedDate,
        ];
    }
    function saveBackupLog($name,$diskName,$fullPath):BackupLog
    {
        $userBackupsCount = BackupLog::where('user_id', auth()->id())->count();

        if ($userBackupsCount > 3) {
            BackupLog::where('user_id', auth()->id())
                ->orderBy('downloaded_at', 'desc')
                ->skip(3)
                ->take(PHP_INT_MAX)
                ->delete();
        }

        return BackupLog::create([
            'file_name' => $name,
            'disk_name' => $diskName,
            'downloaded_at' => now(),
            'user_id' => auth()->id() ?? null,
            'full_path' => $fullPath,
        ]);
    }

}
