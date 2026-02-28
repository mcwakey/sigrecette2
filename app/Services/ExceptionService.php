<?php

namespace App\Services;


use App\Contracts\ExceptionServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class ExceptionService implements ExceptionServiceInterface
{
    public function getMessage(\Throwable $e, ?string $message = null): string
    {
        return config('app.debug') || ! app()->environment('production') ?
            $e->getMessage() :
            ($message ?? __('frontend.errors.bad_request'));
    }
    public function getStatusCode(\Throwable $e):int{
        $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        if (!in_array($code, [Response::HTTP_FORBIDDEN, Response::HTTP_NOT_FOUND])) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
       return $code;
    }
}
