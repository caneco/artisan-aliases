<?php

namespace Caneco\ArtisanAliases;

use Illuminate\Support\Facades\Config;

class AliasFile
{
    public static function local(): string
    {
        return base_path('.laravel_aliases');
    }

    public static function global(): string
    {
        return $_SERVER['HOME'] . '/.laravel_aliases';
    }
}