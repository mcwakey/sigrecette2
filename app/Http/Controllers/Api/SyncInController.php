<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaxpayerStateEnums;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SyncInController extends Controller
{
    private string $new = 'new';

    public function syncIn(Request $request)
    {
        $data = $request->input('data', []);
        $step = 'initializing';

        DB::beginTransaction();
        try {
            $invoiceUpdates = [];
            $paymentInserts = [];

            foreach ($data as $taxpayerGroup) {
                foreach ($taxpayerGroup as $taxpayerData) {
                    foreach ($taxpayerData as $value) {
                        $userId = $value['userId'] ?? null;
                        $taxpayerId = $value['_id'] ?? null;
                        $taxpayerStatus = $value['dataStatus'] ?? null;
                        $taxpayerTaxables = $value['taxpayerTaxables'] ?? [];
                        $taxpayerInvoices = $value['invoices'] ?? [];
                        $taxpayerPayments = $value['payments'] ?? [];
                        unset($value['_id'], $value['time'], $value['ereaId'], $value['userId'], $value['dataStatus'], $value['taxpayerTaxables'], $value['invoices'], $value['payments']);
                        $value['from_mobile_and_validate_state'] = TaxpayerStateEnums::PENDING->value;

                        if ($taxpayerStatus !== null) {
                            if ($taxpayerStatus == $this->new) {
                                $step = 'creating taxpayer (id: ' . ($taxpayerId ?? 'unknown') . ')';
                                $value['createdBy'] = $userId;
                                $taxpayer = Taxpayer::create($this->transformKeysToSnakeCase($value));
                                $taxpayerId = $taxpayer->id;
                            } else {
                                $step = 'updating taxpayer (id: ' . $taxpayerId . ')';
                                $value['updatedBy'] = $userId;
                                Taxpayer::where('id', $taxpayerId)
                                    ->update($this->transformKeysToSnakeCase($value));
                            }
                        }

                        // Batch collect new taxables, update existing ones
                        $newTaxables = [];
                        foreach ($taxpayerTaxables as $taxpayerTaxable) {
                            $taxableStatus = $taxpayerTaxable['dataStatus'] ?? null;
                            if ($taxableStatus !== null) {
                                $taxpayerTaxable['taxpayer_id'] = $taxpayerId;
                                $taxableId = $taxpayerTaxable['_id'] ?? null;
                                unset($taxpayerTaxable['_id'], $taxpayerTaxable['dataStatus']);
                                $transformed = $this->transformKeysToSnakeCase($taxpayerTaxable);
                                if ($taxableStatus == $this->new) {
                                    $newTaxables[] = $transformed;
                                } else {
                                    $step = 'updating taxpayer taxable (id: ' . ($taxableId ?? 'unknown') . ')';
                                    TaxpayerTaxable::where('id', $taxableId)
                                        ->update($transformed);
                                }
                            }
                        }
                        if (!empty($newTaxables)) {
                            $step = 'inserting ' . count($newTaxables) . ' taxable(s) for taxpayer (id: ' . $taxpayerId . ')';
                            TaxpayerTaxable::insert($newTaxables);
                        }

                        // Collect invoice updates for batch processing
                        foreach ($taxpayerInvoices as $taxpayerInvoice) {
                            $invoiceStatus = $taxpayerInvoice['dataStatus'] ?? null;
                            if ($invoiceStatus !== null) {
                                $invoiceId = $taxpayerInvoice['_id'];
                                unset($taxpayerInvoice['_id'], $taxpayerInvoice['dataStatus']);
                                $invoiceUpdates[$invoiceId] = $this->transformKeysToSnakeCase($taxpayerInvoice);
                            }
                        }

                        // Collect payment inserts for batch processing
                        foreach ($taxpayerPayments as $taxpayerPayment) {
                            $paymentStatus = $taxpayerPayment['dataStatus'] ?? null;
                            if ($paymentStatus !== null) {
                                unset($taxpayerPayment['_id'], $taxpayerPayment['dataStatus']);
                                $paymentInserts[] = $this->transformKeysToSnakeCase($taxpayerPayment);
                            }
                        }
                    }
                }
            }

            // Batch update invoices
            foreach ($invoiceUpdates as $invoiceId => $updateData) {
                $step = 'updating invoice (id: ' . $invoiceId . ')';
                Invoice::where('id', $invoiceId)->update($updateData);
            }

            // Batch insert payments
            if (!empty($paymentInserts)) {
                $step = 'inserting ' . count($paymentInserts) . ' payment(s)';
                foreach (array_chunk($paymentInserts, 500) as $chunk) {
                    Payment::insert($chunk);
                }
            }

            DB::commit();
            return response()->json(true, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('SyncIn failed at step [' . $step . ']: ' . $e->getMessage(), [
                'step' => $step,
                'exception' => $e,
            ]);
            return response()->json([
                'error' => 'Data sync failed',
                'step' => $step,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
    private function transformKeysToSnakeCase(array $data)
    {
        $snakeCaseData = [];
        foreach ($data as $key => $value) {
            $snakeCaseKey = Str::snake($key);
            $snakeCaseData[$snakeCaseKey] = is_array($value) ? $this->transformKeysToSnakeCase($value) : $value;
        }
        return $snakeCaseData;
    }
}
