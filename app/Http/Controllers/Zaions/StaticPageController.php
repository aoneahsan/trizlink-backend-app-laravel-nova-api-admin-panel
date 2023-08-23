<?php

namespace App\Http\Controllers\Zaions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function show()
    {
        return response()->json(['data' => 'if you get this then the page is not properly configured', 'learn more' => 'https://novapackages.com/packages/whitecube/nova-page']);
    }
}
