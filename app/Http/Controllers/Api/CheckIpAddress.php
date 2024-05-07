<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use Illuminate\Http\Request;

class CheckIpAddress extends Controller
{
    public function check(Request $request)
    {
        $commune  = Commune::getFirstCommune();
        $serverip = $request->input('serverip');

        if($commune==null){
            return response()->json([
                'message' => "commune not find",
            ],404);
        }
        return response()->json([
            'commune' =>  $commune?->name,
            'serverip' => $serverip,
        ],200);
    }
}
