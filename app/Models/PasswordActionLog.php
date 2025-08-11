<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordActionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_name',
        'username',
        'user_id',
        'admin_ip_adress',
    ];
}
