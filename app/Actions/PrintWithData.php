<?php

namespace App\Actions;

use App\Contracts\ExceptionServiceInterface;
use App\Contracts\PrintServiceInterface;
use App\Models\PrintFile;
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
            return view("errors.error", [
                "code" => Response::HTTP_BAD_REQUEST,
                "message" => $exceptionService->getMessage($e)
            ]);
        }

    }
}
