<?php

namespace App\Actions;

use App\Contracts\ExceptionServiceInterface;
use App\Contracts\PrintServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PrintWithoutData
{
    public function execute($data = null, $type = null, $action = null, User $id = null)
    {
        $exceptionService = resolve(ExceptionServiceInterface::class);
        try {
            $printService= app(PrintServiceInterface::class);
            if (Storage::missing("exports")) {
                Storage::makeDirectory("exports");
            }
            $data = ($data === 'null' || $data === null) ? session('edition_params', []) : json_decode($data, true);
            $result = $printService->processType($type, $data, $action, $id);
            if ($result['success']) {
                return $result['pdf'];
            }
            session()->forget('edition_params');
            return back()->with('error', $result['message']);
        }catch (\Throwable $e) {
            $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            if (!in_array($code, [Response::HTTP_FORBIDDEN, Response::HTTP_NOT_FOUND])) {
                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
            return view("errors.{$code}", [
                "code" => $code,
                "message" => $exceptionService->getMessage($e)
            ]);
        }

    }
}
