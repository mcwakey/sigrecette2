<?php

namespace App\Jobs;

use App\Models\UserLogs;

class LogUserActivity
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        UserLogs::create($this->data);
    }
}

