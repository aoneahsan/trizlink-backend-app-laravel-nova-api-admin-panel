<?php

namespace App\Http\Controllers\Zaions\ZLink\TimeSlot;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\TimeSlot\TimeSlotResource;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\TimeSlot\TimeSlot;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use Illuminate\Http\Request;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Support\Facades\Gate;

class TimeSlotController extends Controller
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

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_timeSlot->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No workspace found!']
                ]);
            }

            $itemsCount = TimeSlot::where('workspaceId', $workspace->id)->count();

            $items = TimeSlot::where('workspaceId', $workspace->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => TimeSlotResource::collection($items),
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
    public function store(Request $request, $workspaceId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_timeSlot->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }

        $request->validate([
            'time' => 'required|string|max:250',
            'day' => 'required|string|max:250',
            'color' => 'required|string|max:250',
            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $result = TimeSlot::create([
                'uniqueId' => uniqid(),
                'createdBy' => $currentUser->id,
                'workspaceId' => $workspace->id,

                'time' => $request->has('time') ? $request->time : null,
                'day' => $request->has('day') ? $request->day : null,
                'color' => $request->has('color') ? $request->color : null,

                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,

                'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new TimeSlotResource($result)
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
    public function show(Request $request, $workspaceId, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_timeSlot->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }

        try {
            $item = TimeSlot::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new TimeSlotResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Time slot not found!']
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
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_timeSlot->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }

        $request->validate([
            'time' => 'required|string|max:250',
            'day' => 'required|string|max:250',
            'color' => 'required|string|max:250',
            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $item = TimeSlot::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->update([
                    'time' => $request->has('time') ? $request->time : $item->time,
                    'day' => $request->has('day') ? $request->day : $item->day,
                    'color' => $request->has('color') ? $request->color : $item->color,

                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->sortOrderNo,

                    'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : $item->extraAttributes,
                ]);

                $item = TimeSlot::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new TimeSlotResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Time slot not found!']
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
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_timeSlot->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }

        try {
            $item = TimeSlot::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse(['item' => ['success' => true]]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Time slot not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
