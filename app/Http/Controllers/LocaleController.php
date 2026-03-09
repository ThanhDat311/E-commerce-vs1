<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Switch the application locale.
     */
    public function setLocale($lang)
    {
        if (in_array($lang, ['en', 'vi'])) {
            Session::put('locale', $lang);
            App::setLocale($lang);
        }

        return back();
    }
}
