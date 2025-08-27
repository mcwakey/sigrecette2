<?php

namespace App\Actions;

use App\Contracts\ExceptionServiceInterface;
use App\Dtos\InvoiceBatchDTO;
use App\Helpers\Constants;
use App\Jobs\DownloadInvoiceZipJob;
use App\Models\Invoice;
class DownloadMultipleInvoiceAction
{
    public function execute($action)
    {
        $exceptionService = resolve(ExceptionServiceInterface::class);
        try {
            $urlParts = parse_url(url()->previous());
            $state = null;
            parse_str($urlParts['query'] ?? '', $queryParams);
            if (isset($queryParams['state']) && array_key_exists($queryParams['state'], Constants::INVOICE_STATE_PRINTABLE_MAP)) {
                $state = Constants::INVOICE_STATE_PRINTABLE_MAP[$queryParams['state']];
            }
            $uuids = Invoice::getPrintableUuid($state ?? null);
            if (count($uuids) == 0) {
                return back()->with('error', 'no Data');
            } else {
              DownloadInvoiceZipJob::dispatch(new InvoiceBatchDTO($uuids, $action),auth()->user());
            }
            return back();
        }catch (\Throwable $e) {
            $code =$exceptionService->getStatusCode($e);
            return view("errors.{$code}", [
                "code" => $code,
                "message" => $exceptionService->getMessage($e)
            ]);
        }

    }
}
