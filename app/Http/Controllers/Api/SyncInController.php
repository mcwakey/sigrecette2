<?php

namespace App\Http\Controllers\Api;

use App\Models\Taxpayer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TaxpayerTaxable;

use Illuminate\Support\Facades\DB;

class SyncInController extends Controller
{
    private string $new = 'new';

    public function syncIn(Request $request)
    {
        $data = $request->input('data', []);
        
        // foreach ($data as $taxpayer) {
        //     foreach ($taxpayer as $taxpayerData) {
        //         foreach ($taxpayerData as $value) {

        //             $taxpayerId = $value['_id'] ?? null;
        //             $taxpayerTaxables = $value['taxpayerTaxables'] ?? [];
        //             $taxpayerInvoices = $value['invoices'] ?? [];
        //             $taxpayerPayments = $value['payments'] ?? [];
                    
        //             unset($value['ereaId']);
                    
        //             if (empty($value['dataStatus']) || isset($value['dataStatus'])) {
        //                 if($value['dataStatus'] == $this->new){
        //                     $value['from_mobile_and_validate_state'] = 'PENDING';
                            
        //                     $taxpayer = Taxpayer::create($this->transformKeysToSnakeCase($value));
        //                     //on doit avoir klk chose come xa pour reactualiser le taxpayerId si cest un nouveau taxpayer
        //                     $taxpayerId = $taxpayer->id;
        //                 }else{
        //                     Taxpayer::find($taxpayerId)?->update($this->transformKeysToSnakeCase($value));
        //                 }
        //             }

        //             foreach ($taxpayerTaxables as $taxpayerTaxable) {
        //                 if(empty($taxpayerTaxable['dataStatus']) || isset($taxpayerTaxable['dataStatus'])){

        //                     $taxpayerTaxable['taxpayer_id'] = $taxpayerId;

        //                     if($taxpayerTaxable['dataStatus'] == $this->new){
        //                         TaxpayerTaxable::create($this->transformKeysToSnakeCase($taxpayerTaxable));
        //                     }else{
        //                         TaxpayerTaxable::find($taxpayerTaxable['_id'])?->update($this->transformKeysToSnakeCase($taxpayerTaxable));
        //                     }
        //                 }
        //             }

        //             foreach ($taxpayerInvoices as $taxpayerInvoice) {
        //                 if(empty($taxpayerInvoice['dataStatus']) || isset($taxpayerInvoice['dataStatus'])){
        //                     Invoice::find($taxpayerInvoice['_id'])?->update($this->transformKeysToSnakeCase($taxpayerInvoice));
        //                 }
        //             }

        //             foreach ($taxpayerPayments as $taxpayerPayment) {
        //                 if(empty($taxpayerPayment['dataStatus']) || isset($taxpayerPayment['dataStatus'])){
        //                     Payment::updateOrCreate(['id' => $taxpayerPayment['_id']], $this->transformKeysToSnakeCase($taxpayerPayment));
        //                 }
        //             }
        //         }
        //     }
        // }

        // return response()->json(true, 200);

        // Start the transaction
        DB::beginTransaction();

        try {
            foreach ($data as $taxpayer) {
                foreach ($taxpayer as $taxpayerData) {
                    foreach ($taxpayerData as $value) {

                        $taxpayerId = $value['_id'] ?? null;
                        $taxpayerTaxables = $value['taxpayerTaxables'] ?? [];
                        $taxpayerInvoices = $value['invoices'] ?? [];
                        $taxpayerPayments = $value['payments'] ?? [];

                        unset($value['ereaId']);

                        if (empty($value['dataStatus']) || isset($value['dataStatus'])) {
                            if ($value['dataStatus'] == $this->new) {
                                $value['from_mobile_and_validate_state'] = 'PENDING';

                                // Create a new taxpayer
                                $taxpayer = Taxpayer::create($this->transformKeysToSnakeCase($value));
                                // Update taxpayerId if it's a new taxpayer
                                $taxpayerId = $taxpayer->id;
                            } else {
                                // Update existing taxpayer
                                Taxpayer::find($taxpayerId)?->update($this->transformKeysToSnakeCase($value));
                            }
                        }

                        // Process taxpayer taxables
                        foreach ($taxpayerTaxables as $taxpayerTaxable) {
                            if (empty($taxpayerTaxable['dataStatus']) || isset($taxpayerTaxable['dataStatus'])) {

                                $taxpayerTaxable['taxpayer_id'] = $taxpayerId;

                                if ($taxpayerTaxable['dataStatus'] == $this->new) {
                                    TaxpayerTaxable::create($this->transformKeysToSnakeCase($taxpayerTaxable));
                                } else {
                                    TaxpayerTaxable::find($taxpayerTaxable['_id'])?->update($this->transformKeysToSnakeCase($taxpayerTaxable));
                                }
                            }
                        }

                        // Process taxpayer invoices
                        foreach ($taxpayerInvoices as $taxpayerInvoice) {
                            if (empty($taxpayerInvoice['dataStatus']) || isset($taxpayerInvoice['dataStatus'])) {
                                Invoice::find($taxpayerInvoice['_id'])?->update($this->transformKeysToSnakeCase($taxpayerInvoice));
                            }
                        }

                        // Process taxpayer payments
                        foreach ($taxpayerPayments as $taxpayerPayment) {
                            if (empty($taxpayerPayment['dataStatus']) || isset($taxpayerPayment['dataStatus'])) {
                                Payment::updateOrCreate(['id' => $taxpayerPayment['_id']], $this->transformKeysToSnakeCase($taxpayerPayment));
                            }
                        }
                    }
                }
            }

            // Commit the transaction if all operations are successful
            DB::commit();

            return response()->json(true, 200);
        } catch (\Exception $e) {
            // Rollback the transaction if any operation fails
            DB::rollBack();

            // Log the error (optional)
            // \Log::error('Error in syncIn: ' . $e->getMessage());

            return response()->json(['error' => 'Data sync failed'], 500);
        }
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
