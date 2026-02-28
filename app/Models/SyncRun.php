<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'direction',
        'entity',
        'status',
        'started_at',
        'finished_at',
        'processed',
        'succeeded',
        'failed',
        'cursor',
        'error_sample',
        'meta',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'error_sample' => 'array',
        'meta' => 'array',
    ];
}
