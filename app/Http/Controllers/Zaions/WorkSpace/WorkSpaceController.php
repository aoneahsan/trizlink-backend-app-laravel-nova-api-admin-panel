<?php

namespace App\Http\Controllers\Zaions\WorkSpace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\WorkSpace\WorkSpaceResource;
use App\Models\Default\WorkSpace;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WorkSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $itemsCount = WorkSpace::where('userId', $currentUser->id)->count();
            $items = WorkSpace::where('userId', $currentUser->id)->with('user')->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => WorkSpaceResource::collection($items),
                    'itemsCount' => $itemsCount,
                ],
                'status' => 200
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index2(Request $request, $pageNumber, $paginationLimit)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $itemsQuery = WorkSpace::query()->where('userId', $currentUser->id)->with('user');
            $paginationOffset = '';

            if (intval($pageNumber) && $pageNumber > -1) {
                $paginationOffset = intval($paginationLimit) * intval($pageNumber);
                $itemsQuery = $itemsQuery->skip($paginationOffset);
            } else {
                $itemsQuery = $itemsQuery->skip(0);
            }

            if (intval($paginationLimit) && $paginationLimit > 0) {
                $itemsQuery = $itemsQuery->limit($paginationLimit)->get();
            } else {
                $itemsQuery = $itemsQuery->limit(config('zLinkConfig.defaultLimit'))->get();
            }

            $itemsCount = $itemsQuery->count();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => WorkSpaceResource::collection($itemsQuery),
                    'itemsCount' => $itemsCount,
                    'pageNumber' => $pageNumber,
                    'paginationLimit' => $paginationLimit,
                    'paginationOffset' => $paginationOffset
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
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:200',
                'timezone' => 'nullable|string|max:200',
                'workspaceImage' => 'nullable|string',
                'internalPost' => 'nullable|boolean',
                'workspaceData' => 'nullable|json',
                'isFavorite' => 'nullable|boolean',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $result = WorkSpace::create([
                'uniqueId' => uniqid(),

                'userId' => $currentUser->id,
                'title' => $request->has('title') ? $request->title : null,
                'timezone' => $request->has('timezone') ? $request->timezone : null,
                'workspaceImage' => $request->has('workspaceImage') ? $request->workspaceImage : null,
                'internalPost' => $request->has('internalPost') ? $request->internalPost : false,
                'workspaceData' => $request->has('workspaceData') ? (is_string($request->workspaceData) ? json_decode($request->workspaceData) : $request->workspaceData) : null,
                'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : false,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : true,
                'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WorkSpaceResource($result)
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
    public function show(Request $request, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WorkSpaceResource($item)
                ]);
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
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'timezone' => 'nullable|string|max:200',
            'workspaceImage' => 'nullable|string',
            'internalPost' => 'nullable|boolean',
            'workspaceData' => 'nullable|json',
            'isFavorite' => 'nullable|boolean',

            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->update([
                    'title' => $request->has('title') ? $request->title : $item->workspaceName,
                    'timezone' => $request->has('timezone') ? $request->timezone : $item->timezone,
                    'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : false,
                    'workspaceImage' => $request->has('workspaceImage') ? $request->workspaceImage : $item->workspaceImage,
                    'internalPost' => $request->has('internalPost') ? $request->internalPost : $item->internalPost,
                    'workspaceData' => $request->has('workspaceData') ? (is_string($request->workspaceData) ? json_decode($request->workspaceData) : $request->workspaceData) : $request->workspaceData,


                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->sortOrderNo,
                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : $item->extraAttributes,
                ]);

                $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WorkSpaceResource($item)
                ]);
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
    public function destroy(Request $request, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


        try {
            $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => ['success' => true]
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function updateIsFavorite(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'isFavorite' => 'required|boolean',
            ]);

            $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {

                $item->update([
                    'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : $item->isFavorite,
                ]);

                $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WorkSpaceResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
