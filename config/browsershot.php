<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Node Binary Path
    |--------------------------------------------------------------------------
    | The full path to the node executable. Needed when running from a web
    | server process (Apache/Nginx) that may not have node on its PATH.
    */
    'node_binary' => env('BROWSERSHOT_NODE_BINARY', 'C:/Program Files/nodejs/node.exe'),

    /*
    |--------------------------------------------------------------------------
    | NPM Binary Path
    |--------------------------------------------------------------------------
    | The full path to the npm executable.
    */
    'npm_binary' => env('BROWSERSHOT_NPM_BINARY', 'C:/Program Files/nodejs/npm.cmd'),

    /*
    |--------------------------------------------------------------------------
    | Node Modules Path
    |--------------------------------------------------------------------------
    | Directory that contains the node_modules folder with puppeteer installed.
    | Defaults to the Laravel application base path.
    */
    'node_modules_path' => env('BROWSERSHOT_NODE_MODULES_PATH', base_path()),

    /*
    |--------------------------------------------------------------------------
    | Chrome / Chromium Executable Path
    |--------------------------------------------------------------------------
    | Optional. If set, Browsershot will use this path instead of letting
    | puppeteer resolve it automatically. Set BROWSERSHOT_CHROME_PATH in .env
    | if the auto-discovered path is not stable (e.g. after updates).
    */
    'chrome_path' => env('BROWSERSHOT_CHROME_PATH', ''),
];
