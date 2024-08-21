<?php

namespace App\Http\Controllers;

use App\DataTables\ExportInvoicesDataTable;
use App\DataTables\ExportRecoveriesDataTable;
use App\DataTables\ExportTaxpayersDataTable;
use App\DataTables\PrintablesDataTable;
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
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
class ExportController extends Controller
{
    public function index(Request $request,ExportTaxpayersDataTable $exportTaxpayersDataTable,ExportInvoicesDataTable $exportInvoicesDataTable,ExportRecoveriesDataTable $exportRecoveriesDataTable)

    {
        $year = Year::getActiveYear()->name;
        $validatedData = $request->validate([
            's_date' => 'nullable|date_format:Y-m-d H:i:s',
            'e_date' => 'nullable|date_format:Y-m-d H:i:s',
            'export_type' => ['string', Rule::in(array_keys(Constants::EXPORT_VALIDATION_MAP))],
            'disable'=>['nullable', 'integer', Rule::in(1)],
            'state'=>['nullable', 'string', Rule::in('at')],
        ]);
        $export_type = isset($validatedData['export_type'])?Constants::EXPORT_VALIDATION_MAP[$validatedData['export_type']] : null;
        $tax_labels = TaxLabel::all();
        if($export_type==ExportTypeEnums::TAXPAYER){
            $disable =$validatedData['disable']??null;
            $state = $validatedData['state']??null;
            $startDate = $validatedData['s_date'] ?? null;
            $endDate = $validatedData['e_date'] ?? null;
            $zones = Zone::all();
            $categories = Category::all();
            $towns = Town::all();
            $cantons = Canton::all();
            $activities = Activity::all();
            return $exportTaxpayersDataTable->with(
                [
                    'state'=>$state,
                    'disable' => $disable,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            )->render('pages/export.taxpayers.list',compact('zones','categories','towns','cantons','activities'));
        }elseif ($export_type==ExportTypeEnums::INVOICE){
            $startDate = $validatedData['s_date'] ??  Carbon::parse("{$year}-01-01 00:00:00");
            $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
            $zones = Zone::all();

            return $exportInvoicesDataTable->with(

                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            )->render('pages/export.invoices.list', compact('zones', 'tax_labels'));


        }else{
            $startDate = $validatedData['s_date'] ??  Carbon::parse("{$year}-01-01 00:00:00");
            $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
            return $exportRecoveriesDataTable->with(
                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            )->render('pages/export.recoveries.list', compact( 'tax_labels'));
        }

    }
    public function backup()

    {
        return view('pages/export/backup.show');
    }
    public function backupDownload()
    {

        Artisan::call('backup:run');

      //  $outputText = Artisan::output();


       // dd($outputText);


        $diskName = config('backup.backup.destination.disks')[0];

        // Construire le chemin complet avec le nom de l'application
        $rootPath = config('filesystems.disks.' . $diskName . '.root');
        //$fullBackupPath = $rootPath . DIRECTORY_SEPARATOR . config('app.name');

        // Lister les fichiers de sauvegarde
        $file = collect(Storage::disk($diskName)->files(config('app.name')))
            ->filter(fn($file) => Str::endsWith($file, '.zip'))
            ->sortByDesc(fn($file) => Storage::disk($diskName)->lastModified($file))
            ->first();

        if (!$file) {
            return response()->json([
                'error' => 'Aucune sauvegarde trouvée',
                'disk_name' => $diskName,
            ], 404);
        }
        $fullPath = realpath($rootPath . DIRECTORY_SEPARATOR . $file);


        if ($fullPath) {
            return response()->download($fullPath);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
