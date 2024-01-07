<?php

namespace App\Http\Controllers\Zaions\ZLink\Plans;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\Plans\PlanResource;
use App\Models\ZLink\Plans\Plan;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;

class PlanController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $items = Plan::get();

            return ZHelpers::sendBackRequestCompletedResponse([
                'items' => PlanResource::collection($items),
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
