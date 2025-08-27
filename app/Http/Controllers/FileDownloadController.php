<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileDownloadController extends Controller
{
   public function download($filename,$id){
       $filePath = storage_path("app/exports/{$filename}");
       if(!empty($id)){
           $user = Auth::user();
           $user->notifications->where('id', "=", $id)->markAsRead();
       }
       if (!file_exists($filePath)) {
           abort(404);
       }
       return response()->download($filePath)->deleteFileAfterSend(true);
   }
}
