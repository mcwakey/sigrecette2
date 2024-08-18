<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Taxpayer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TaxpayerTaxable;

class SyncInController extends Controller
{
    private string $new = 'new';

    public function syncIn(Request $request)
    {
        $data = $request->input('data', []);
        
        foreach ($data as $taxpayer) {
            foreach ($taxpayer as $taxpayerData) {
                foreach ($taxpayerData as $value) {

                    $taxpayerId = $value['_id'] ?? null;
                    $taxpayerTaxables = $value['taxpayerTaxables'] ?? [];
                    $taxpayerInvoices = $value['invoices'] ?? [];
                    $taxpayerPayments = $value['payments'] ?? [];
                    
                    unset($value['ereaId']);
                    
                    if (empty($value['dataStatus']) || isset($value['dataStatus'])) {
                        if($value['dataStatus'] == $this->new){
                            // Set taxpayer creation source ??
                            $value['from_mobile_and_validate_state'] = '';
                        }
                        Taxpayer::updateOrCreate(['id' => $taxpayerId], $this->transformKeysToSnakeCase($value));
                    }

                    foreach ($taxpayerTaxables as $taxpayerTaxable) {
                        if(empty($taxpayerTaxable['dataStatus']) || isset($taxpayerTaxable['dataStatus'])){
                            TaxpayerTaxable::updateOrCreate(['id' => $taxpayerTaxable['_id']], $this->transformKeysToSnakeCase($taxpayerTaxable));
                        }
                    }

                    foreach ($taxpayerInvoices as $taxpayerInvoice) {
                        if(empty($taxpayerInvoice['dataStatus']) || isset($taxpayerInvoice['dataStatus'])){
                            TaxpayerTaxable::updateOrCreate(['id' => $taxpayerInvoice['_id']], $this->transformKeysToSnakeCase($taxpayerInvoice));
                        }
                    }

                    foreach ($taxpayerPayments as $taxpayerPayment) {
                        if(empty($taxpayerPayment['dataStatus']) || isset($taxpayerPayment['dataStatus'])){
                            TaxpayerTaxable::updateOrCreate(['id' => $taxpayerPayment['_id']], $this->transformKeysToSnakeCase($taxpayerPayment));
                        }
                    }
                }
            }
        }

        return response()->json(true, 200);
    }

    private function transformKeysToSnakeCase(array $data)
    {
        $snakeCaseData = [];
        foreach ($data as $key => $value) {
            $snakeCaseKey = Str::snake($key);
            if (is_array($value)) {
                $snakeCaseData[$snakeCaseKey] = $this->transformKeysToSnakeCase($value);
            } else {
                $snakeCaseData[$snakeCaseKey] = $value;
            }
        }
        return $snakeCaseData;
    }
}
