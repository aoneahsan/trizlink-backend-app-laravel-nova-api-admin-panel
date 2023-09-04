<?php

namespace App\Http\Controllers\Zaions\WorkSpace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\WorkSpace\SharedWSResource;
use App\Models\Default\WSTeamMember;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;

class SharedWSController extends Controller
{
    public function index(Request $request)
    {
        try {
            // auth
            $user = $request->user();
            // check user
            $userId = $user->id;

            $sharedWSs = WSTeamMember::where('memberId', $userId)->with('workspace')->get();
            $sharedWSsCount = WSTeamMember::where('memberId', $userId)->with('workspace')->count();


            return ZHelpers::sendBackRequestCompletedResponse(
                [
                    // 'items' => SharedWSResource::collection($sharedWSs),
                    'items' => SharedWSResource::collection($sharedWSs),
                    'count' => $sharedWSsCount
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
