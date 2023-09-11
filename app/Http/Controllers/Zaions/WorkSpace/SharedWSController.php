<?php

namespace App\Http\Controllers\Zaions\WorkSpace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\WorkSpace\SharedWSResource;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
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
            $sharedWSs = WSTeamMember::where('memberId', $userId)->where('accountStatus',  '!=', WSMemberAccountStatusEnum::rejected->value)->with('workspace')->get();
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

    public function updateIsFavorite(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();

            $request->validate([
                'isFavorite' => 'required|boolean',
            ]);

            $item = WSTeamMember::where('uniqueId', $itemId)->where('memberId', $currentUser->id)->where('accountStatus',  '!=', WSMemberAccountStatusEnum::rejected->value)->with('workspace')->first();

            if ($item) {

                $item->update([
                    'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : $item->isFavorite,
                ]);

                $item = WSTeamMember::where('uniqueId', $itemId)->where('memberId', $currentUser->id)->where('accountStatus',  '!=', WSMemberAccountStatusEnum::rejected->value)->with('workspace')->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new SharedWSResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
