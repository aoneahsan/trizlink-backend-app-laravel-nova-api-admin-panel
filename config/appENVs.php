<?php


return [
    'FRONTEND_URL' => env('APP_ENV') === 'local' ?  env('FRONTEND_URL_LOCAL', '') : env('FRONTEND_URL_PROD', '')
];