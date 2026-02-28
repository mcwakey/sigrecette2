<?php

namespace App\Contracts;

use App\Models\User;

interface PrintServiceInterface
{
    public function processType($type, $data, $action, User $user = null): array;
}
