<?php
namespace App\Http\Controllers;
use App\DataTables\ExportInvoicesDataTable;
use App\DataTables\ExportRecoveriesDataTable;
use App\DataTables\ExportTaxpayersDataTable;
use App\DataTables\ExportTaxpayerTaxablesDataTable;
use App\Enums\ExportTypeEnums;
use App\Helpers\Constants;
use App\Models\Activity;
use App\Models\Canton;
use App\Models\Category;
use App\Models\TaxLabel;
use App\Models\Town;
use App\Models\Year;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class ExportController extends Controller
{
    public function index(Request $request,
                          ExportTaxpayersDataTable $exportTaxpayersDataTable,
                          ExportInvoicesDataTable $exportInvoicesDataTable,
                          ExportRecoveriesDataTable $exportRecoveriesDataTable,
    ExportTaxpayerTaxablesDataTable $exportTaxpayerTaxablesDataTable,)
    {
        $year = Year::getActiveYear()->name;
        $validatedData = $request->validate([
            's_date' => 'nullable|date_format:Y-m-d H:i:s',
            'e_date' => 'nullable|date_format:Y-m-d H:i:s',
            'export_type' => ['string', Rule::in(array_keys(Constants::EXPORT_VALIDATION_MAP))],
            'disable' => ['nullable', 'integer', Rule::in(1)],
            'state' => ['nullable', 'string', Rule::in('at')],
        ]);
        $export_type = isset($validatedData['export_type']) ? Constants::EXPORT_VALIDATION_MAP[$validatedData['export_type']] : null;
        $tax_labels = TaxLabel::all();
        if ($export_type == ExportTypeEnums::TAXPAYER) {
            $disable = $validatedData['disable'] ?? null;
            $state = $validatedData['state'] ?? null;
            $startDate = $validatedData['s_date'] ?? null;
            $endDate = $validatedData['e_date'] ?? null;
            $zones = Zone::all();
            $categories = Category::all();
            $towns = Town::all();
            $cantons = Canton::all();
            $activities = Activity::all();
            return $exportTaxpayersDataTable->with(
                [
                    'state' => $state,
                    'disable' => $disable,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            )->render('pages/export.taxpayers.list', ['zones' => $zones, 'categories' => $categories, 'towns' => $towns, 'cantons' => $cantons, 'activities' => $activities]);
        } elseif ($export_type == ExportTypeEnums::INVOICE) {
            $startDate = $validatedData['s_date'] ?? Carbon::parse("{$year}-01-01 00:00:00");
            $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
            $zones = Zone::all();
            return $exportInvoicesDataTable->with(
                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            )->render('pages/export.invoices.list', ['zones' => $zones, 'tax_labels' => $tax_labels]);
        } elseif ($export_type == ExportTypeEnums::TAXPAYER_TAXABLE){
            $startDate = $validatedData['s_date'] ?? Carbon::parse("{$year}-01-01 00:00:00");
            $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
            return $exportTaxpayerTaxablesDataTable->with(
                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            )->render('pages/export.taxpayer_taxables.list', []);
        }
        else {
            $startDate = $validatedData['s_date'] ?? Carbon::parse("{$year}-01-01 00:00:00");
            $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
            return $exportRecoveriesDataTable->with(
                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            )->render('pages/export.recoveries.list', ['tax_labels' => $tax_labels]);
        }
    }
    public function backup()
    {
        return view('pages/export/backup.show');
    }
    public function backupDownload()
    {
        if (PHP_OS_FAMILY !== 'Linux') {
            return response()->json([
                'error' => 'Cette action est uniquement disponible sur un environnement Linux.'
            ], 403);
        }

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

        $fullPath = realpath($rootPath . DIRECTORY_SEPARATOR . $latestBackup);

        if ($fullPath) {
            return response()->download($fullPath);
        } else {
            return response()->json(['error' => 'Fichier introuvable'], 404);
        }
    }
}
