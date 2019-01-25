<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Artisan Alias Master Switch
    |--------------------------------------------------------------------------
    | This option may be used to enable/disable all Artisan alias
    | defined in your local or global `.laravel_alias` file
    */
    'enabled' => env('ARTISAN_ALIAS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Default Alias File
    |--------------------------------------------------------------------------
    | This option allows you to have three ways of load the list of alias. The
    | `global` option will only load the alias defined in your home directory,
    | while the `local` option, will limit the alias from the list in your
    | application. Finally, The `both` option, or anything else, will
    | load the alias from both locations.
    |
    | Supported: "global", "local", "both",
    */
    'use_only' => 'both',

];
