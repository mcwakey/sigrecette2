<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SyncInController extends Controller
{
    public function syncIn()
    {
        return response()->json(true, 200);
    }
}
