<?php

namespace App\Jobs;

use App\Models\UserLogs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogUserActivity implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
