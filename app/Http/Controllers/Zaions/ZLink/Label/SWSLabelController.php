<?php

namespace App\Http\Controllers\Zaions\ZLink\Label;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\Label\LabelResource;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Models\ZLink\Label\Label;
use Illuminate\Http\Request;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Support\Facades\Gate;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
use App\Zaions\Enums\WSPermissionsEnum;

class SWSLabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $itemId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_label->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No workspace found!']
                ]);
            }

            $itemsCount = Label::where('workspaceId', $workspace->id)->count();

            $items = Label::where('workspaceId', $workspace->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => LabelResource::collection($items),
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
    public function store(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $itemId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::create_sws_label->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No workspace found!']
                ]);
            }

            $request->validate([
                'title' => 'required|string|max:250',
                'color' => 'nullable|string|max:250',
                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);


            $result = Label::create([
                'uniqueId' => uniqid(),
                'createdBy' => $member->memberId,
                'workspaceId' => $workspace->id,

                'title' => $request->has('title') ? $request->title : null,
                'color' => $request->has('color') ? $request->color : null,

                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,

                'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LabelResource($result)
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

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_label->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No workspace found!']
                ]);
            }


            $item = Label::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LabelResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Label not found!']
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
    public function update(Request $request, $memberId, $itemId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_label->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No workspace found!']
                ]);
            }

            $request->validate([
                'title' => 'required|string|max:250',
                'color' => 'nullable|string|max:250',
                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);


            $item = Label::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->update([
                    'title' => $request->has('title') ? $request->title : $item->title,
                    'color' => $request->has('color') ? $request->color : $item->color,

                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->sortOrderNo,

                    'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : $item->extraAttributes,
                ]);

                $item = Label::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LabelResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Label not found!']
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

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::delete_sws_label->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No workspace found!']
                ]);
            }

            $item = Label::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse(['item' => ['success' => true]]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Label not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
