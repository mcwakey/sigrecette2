<?php

namespace App\Traits;

use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

trait HandlesTaxpayerFilters
{
    protected bool $profile_page = false;

    public function getTaxpayerId($id)
    {
        if ($id == null) {
            $previousUrl = url()->previous();
            $previousRoute = Route::getRoutes()->match(Request::create($previousUrl));
            if ($previousRoute->getName() === "taxpayers.show") {
                $this->profile_page = true;
                $segments = explode('/', parse_url($previousUrl, PHP_URL_PATH));
                return   end($segments) ? intval(end($segments)) : null;
            }
        }
        return $id;
    }
}
