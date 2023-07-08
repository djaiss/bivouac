<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelper
{
    /**
     * Return the Markdown text as a parsed string.
     * Also apply a safe mode to get rid of dangerous html.
     */
    public static function parse(string $content = ''): string
    {
        return Str::of($content)->markdown([
            'html_input' => 'strip',
        ]);
    }
}
