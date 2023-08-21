<?php

namespace App\Http\Controllers\Zaions\Workspace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Workspace\WorkspaceTeamResource;
use App\Models\Default\WorkSpace;
use App\Models\Default\WorkspaceTeam;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WorkspaceTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $workspaceId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_workspaceTeam->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                $itemsCount = WorkspaceTeam::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->count();
                $items = WorkspaceTeam::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->get();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'items' => WorkspaceTeamResource::collection($items),
                    'itemsCount' => $itemsCount
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $workspaceId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_workspaceTeam->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                $request->validate([
                    'title' => 'required|string|max:200',
                    'description' => 'nullable|string|max:200',

                    'sortOrderNo' => 'nullable|integer',
                    'isActive' => 'nullable|boolean',
                    'extraAttributes' => 'nullable|json',
                ]);

                $result = WorkspaceTeam::create([
                    'uniqueId' => uniqid(),

                    'userId' => $currentUser->id,
                    'workspaceId' => $workspace->id,

                    'title' => $request->has('title') ? $request->title : null,
                    'description' => $request->has('description') ? $request->description : null,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                    'isActive' => $request->has('isActive') ? $request->isActive : true,
                    'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null,
                ]);

                if ($result) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WorkspaceTeamResource($result)
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['workspace not found!']
                ]);
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
    public function show(Request $request, $workspaceId, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_workspaceTeam->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                $item = WorkSpaceTeam::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                if ($item) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WorkSpaceTeamResource($item)
                    ]);
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Team not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Workspace not found!']
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
    public function update(Request $request, $workspaceId, $itemId)
    {
        try {

            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_workspaceTeam->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                $request->validate([
                    'title' => 'required|string|max:200',
                    'description' => 'nullable|string|max:1000',

                    'sortOrderNo' => 'nullable|integer',
                    'isActive' => 'nullable|boolean',
                    'extraAttributes' => 'nullable|json',
                ]);

                $item = WorkSpaceTeam::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                if ($item) {
                    $item->update([
                        'title' => $request->has('title') ? $request->title : $item->workspaceName,
                        'description' => $request->has('description') ? $request->description : $item->description,

                        'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->sortOrderNo,
                        'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                        'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : $item->extraAttributes,
                    ]);

                    $item = WorkSpaceTeam::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WorkSpaceTeamResource($item)
                    ]);
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Team not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Workspace not found!']
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
    public function destroy(Request $request, $workspaceId, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_workspaceTeam->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                $item = WorkSpaceTeam::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                if ($item) {
                    $item->forceDelete();
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => ['success' => true]
                    ]);
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Team not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
