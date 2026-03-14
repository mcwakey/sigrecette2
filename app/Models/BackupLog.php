<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'disk_name',
        'downloaded_at',
        'user_id',
        'full_path',
    ];
}
