<?php

namespace App\Http\Controllers\Zaions\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\User\UserSettingResource;
use App\Models\Default\UserSetting;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
use App\Zaions\Enums\WSPermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SWSUserSettingController extends Controller
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

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_USSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

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


            $itemsCount = UserSetting::where('workspaceId', $workspace->id)->count();
            $items = UserSetting::where('workspaceId', $workspace->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => UserSettingResource::collection($items),
                    'itemsCount' => $itemsCount
                ],
                'status' => 200
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

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::create_sws_USSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->inviterId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $request->validate([
                'type' => 'required|string|max:200',
                'settings' => 'nullable|json',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            $result = UserSetting::create([
                'uniqueId' => uniqid(),
                'workspaceId' => $workspace->id,

                'createdBy' => $currentUser->id,
                'type' => $request->has('type') ? $request->type : null,
                'settings' => $request->has('settings') ? ZHelpers::zJsonDecode($request->settings) : null,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : true,
                'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserSettingResource($result)
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
    public function show(Request $request, $memberId, $type)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::view_sws_USSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->inviterId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $item = UserSetting::where('type', $type)->where('workspaceId', $workspace->id)->first();


            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserSettingResource($item)
                ]);
            } else {
                // we don't went to show error if not found item
                return ZHelpers::sendBackRequestCompletedResponse(['item' => []]);
                // return ZHelpers::sendBackNotFoundResponse([
                //     'item' => ['Setting not found!']
                // ]);
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
    public function update(Request $request, $memberId, $type)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_USSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->inviterId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $request->validate([
                'type' => 'required|string|max:200',
                'settings' => 'nullable|json',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);


            $item = UserSetting::where('type', $type)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->update([
                    'type' => $request->has('type') ? $request->type : $item->type,
                    'settings' => $request->has('settings') ? ZHelpers::zJsonDecode($request->settings) : $request->settings,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->isActive,
                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : $request->extraAttributes,
                ]);

                $item = UserSetting::where('type', $type)->where('workspaceId', $workspace->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserSettingResource($item)
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Setting not found!']
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
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::delete_sws_USSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->inviterId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $item = UserSetting::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => ['success' => true]
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Setting not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
