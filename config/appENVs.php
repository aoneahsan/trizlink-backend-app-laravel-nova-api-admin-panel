<?php


return [
    'FRONTEND_URL' => env('APP_ENV') === 'local' ?  env('FRONTEND_URL', '') : env('FRONTEND_URL_PROD', '')
];