<?php

namespace App\Actions;

use App\Contracts\ExceptionServiceInterface;
use App\Contracts\PrintServiceInterface;
use App\Models\PrintFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PrintWithData
{

    public function execute(PrintFile $printFile, $type = null, $action = null)
    {
        $exceptionService = resolve(ExceptionServiceInterface::class);

        try {
            $printService= app(PrintServiceInterface::class);
            if (Storage::missing("exports")) {
                Storage::makeDirectory("exports");
            }
            $result = $printService->processType($type, $printFile, $action);
            if ($result['success']) {
                session()->flash('status', 'Ficher Imprimer avec success.');
                return $result['pdf'];
            }
            session()->flash('status', "Erreur lors de la géneration du ficher");
            return back()->with('error', $result['message']);
        }catch (\Throwable $e) {
            Log::error('PDF generation (with data) failed', ['type' => $type, 'action' => $action, 'error' => $e->getMessage()]);
            $code =$exceptionService->getStatusCode($e);
            return view("errors.{$code}", [
                "code" => $code,
                "message" => $exceptionService->getMessage($e)
            ]);
        }

    }
}
