<?php

namespace App\Http\Controllers\Zaions\ZLink\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\Analytics\PixelResource;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Models\ZLink\Analytics\Pixel;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
use App\Zaions\Enums\WSPermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SWSPixelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $memberId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->inviterId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Share workspace not found!']
                ]);
            }

            $itemsCount = Pixel::where('workspaceId', $workspace->id)->count();
            $items = Pixel::where('workspaceId', $workspace->id)->get();

            return ZHelpers::sendBackRequestCompletedResponse([
                'items' => PixelResource::collection($items),
                'itemsCount' => $itemsCount
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $memberId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::create_sws_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->inviterId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Share workspace not found!']
                ]);
            }

            $request->validate([
                'platform' => 'required|string|max:250',
                'title' => 'required|string|max:250',
                'pixelId' => 'required|string|max:250',
                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            $result = Pixel::create([
                'uniqueId' => uniqid(),
                'createdBy' => $currentUser->id,
                'workspaceId' => $workspace->id,
                'platform' => $request->has('platform') ? $request->platform : null,
                'title' => $request->has('title') ? $request->title : null,
                'pixelId' => $request->has('pixelId') ? $request->pixelId : null,
                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive
                    : null,
                'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new PixelResource($result)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $memberId, $itemId)
    {
        try {
        $currentUser = $request->user();
        // first getting the member from member we will get share workspace
        $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

        Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::view_sws_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        if (!$member) {
            return ZHelpers::sendBackNotFoundResponse([
                'item' => ['Share workspace not found!']
            ]);
        }

        // $member->inviterId => id of owner of the workspace
        $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

        if (!$workspace) {
            return ZHelpers::sendBackNotFoundResponse([
                "item" => ['Share workspace not found!']
            ]);
        }


            $item = Pixel::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new PixelResource($item)
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Pixel not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$memberId, $itemId)
    {
        try {       
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();
            
            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
            
            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }
            
            // $member->inviterId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            
            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Share workspace not found!']
                ]);
            }
            
            $request->validate([
                'platform' => 'required|string|max:250',
                'title' => 'required|string|max:250',
                'pixelId' => 'required|string|max:250',
                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            $item = Pixel::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->update([
                    'platform' => $request->has('platform') ? $request->platform : $request->platform,
                    'title' => $request->has('title') ? $request->title : $request->title,
                    'pixelId' => $request->has('pixelId') ? $request->pixelId : $request->pixelId,
                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $request->sortOrderNo,
                    'isActive' => $request->has('isActive') ? $request->isActive
                        : $request->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $request->extraAttributes,
                ]);

                $item = Pixel::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new PixelResource($item)
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Pixel not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $memberId, $itemId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId',  $memberId    )->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();
            
            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::delete_sws_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
            
            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }
            
            // $member->inviterId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            
            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Share workspace not found!']
                ]);
            }

            $item = Pixel::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => ['success' => true]
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Pixel not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
