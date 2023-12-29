<?php

namespace App\Http\Controllers\Zaions\Zlink\ShortLinks;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Zlink\ShortLinks\SLAnalyticsResource;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Models\ZLink\ShortLinks\ShortLink;
use App\Models\ZLink\ShortLinks\SLAnalytics;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Enums\WSEnum;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
use App\Zaions\Enums\WSPermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SLAnalyticsController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type, $wsUniqueId, $slUniqueId)
    {
        try {
            $currentUser = $request->user();

            $workspace = null;
            $shortLink = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_libBlock->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $wsUniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $wsUniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to view any folders.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::create_sws_libBlock->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            }

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $shortLink = ShortLink::where('uniqueId', $slUniqueId)->where('workspaceId', $workspace->id)->first();
            
            if (!$shortLink) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Short link not found!']
                ]);
            }

            $items = SLAnalytics::where('shortLinkId', $shortLink->id)->get();

            return ZHelpers::sendBackRequestCompletedResponse([
                'items' => SLAnalyticsResource::collection($items)
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
