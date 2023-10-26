<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function update(string $locale): RedirectResponse
    {
        if (! in_array($locale, ['en', 'fr'])) {
            abort(400);
        }

        auth()->user()->locale = $locale;
        auth()->user()->save();

        return redirect('/home');
    }
}
