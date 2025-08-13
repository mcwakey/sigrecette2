<?php

namespace App\Services;


use App\Contracts\ExceptionServiceInterface;

class ExceptionService implements ExceptionServiceInterface
{
    public function getMessage(\Throwable $e, ?string $message = null): string
    {
        return config('app.debug') || ! app()->environment('production') ?
            $e->getMessage() :
            ($message ?? __('frontend.errors.bad_request'));
    }
}
