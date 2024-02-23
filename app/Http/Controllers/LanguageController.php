<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    public function setLocale(string $locale)
    {
        if (in_array($locale, config('app.locales'))) {
            App::setLocale($locale);
            session()->put('locale', $locale);
        }

        return Redirect::back();
    }
}
