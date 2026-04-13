<?php

namespace App\Services;

use App\Contracts\DownloadInvoiceZipInterface;
use App\Contracts\PdfGeneratorInterface;
use App\Dtos\InvoiceBatchDTO;
use Illuminate\Support\Facades\Log;
use Spatie\Async\Pool;
use ZipArchive;

class DownloadInvoiceZipService implements DownloadInvoiceZipInterface
{
     public function  execute(InvoiceBatchDTO $dto):array{
         $pdfGenerator = app(PdfGeneratorInterface::class);
         $result['success']=false;
         $zip = new ZipArchive();
         $fileName="multiples_avis.zip";
         $zipFilePath =  storage_path("app/exports/{$fileName}");
         if (file_exists($zipFilePath)) {
             unlink($zipFilePath);
         }
         if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
             $pool = Pool::create();
             foreach ($dto->uuids as $invoiceUid) {
                 $result =$pdfGenerator->generateInvoicePdf([$invoiceUid], 'invoices', $dto->action);
                 if ($result['success']) {
                     $zip->addFromString($result['filename'], $result['pdf']->getContent());
                 } else {
                     Log::warning("Impossible de générer le PDF pour l'UUID: {$invoiceUid}");
                 }
             }
             $pool->wait();
             $zip->close();
             $result['success']=true;
             $result['file_name']=$fileName;
             return $result;
         }
         Log::warning("Impossible de générer le ficher pour le  DTO: {$dto->uuids}");
         return $result;

     }
}
