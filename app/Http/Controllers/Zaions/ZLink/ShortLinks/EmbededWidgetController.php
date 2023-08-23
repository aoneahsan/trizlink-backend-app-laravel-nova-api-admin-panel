<?php

namespace App\Http\Controllers\Zaions\ZLink\ShortLinks;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\ShortLinks\EmbededWidgetResource;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\ShortLinks\EmbededWidget;
use App\Models\ZLink\ShortLinks\ShortLink;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EmbededWidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $workspaceId, $shortLinkId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_embededWidget->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        // getting Short-link in workspace
        $ShortLink = ShortLink::where('uniqueId', $shortLinkId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

        if (!$ShortLink) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No Short-link found!']
            ]);
        }

        try {
            $itemsCount = EmbededWidget::where('userId', $currentUser->id)->count();
            $items = EmbededWidget::where('userId', $currentUser->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => EmbededWidgetResource::collection($items),
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
    public function store(Request $request, $workspaceId, $shortLinkId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_embededWidget->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        // getting Short-link in workspace
        $ShortLink = ShortLink::where('uniqueId', $shortLinkId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

        if (!$ShortLink) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No Short-link found!']
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:250',
            'canCodeJs' => 'nullable|boolean',
            'canCodeHtml' => 'nullable|boolean',
            'jsCode' => 'nullable|string|max:1000',
            'HTMLCode' => 'nullable|string|max:1000',
            'displayAt' => 'nullable|string',
            'delay' => 'nullable|integer',
            'position' => 'nullable|string',
            'animation' => 'nullable|boolean',
            'closingOption' => 'nullable|boolean',

            'isActive' => 'nullable|boolean',
            'sortOrderNo' => 'nullable|integer',
            'extraAttributes' => 'nullable|json'
        ]);
        try {
            $result = EmbededWidget::create([
                'uniqueId' => uniqid(),
                'userId' => $currentUser->id,
                'shortLinkId' => $ShortLink->id,
                'name' => $request->has('name') ? $request->name : null,
                'canCodeJs' => $request->has('canCodeJs') ? $request->canCodeJs : false,
                'canCodeHtml' => $request->has('canCodeHtml') ? $request->canCodeHtml : false,
                'jsCode' => $request->has('jsCode') ? $request->jsCode : null,
                'HTMLCode' => $request->has('HTMLCode') ? $request->HTMLCode : null,
                'displayAt' => $request->has('displayAt') ? $request->displayAt : null,
                'delay' => $request->has('delay') ? $request->delay : null,
                'position' => $request->has('position') ? $request->position : null,
                'animation' => $request->has('animation') ? $request->animation : false,
                'closingOption' => $request->has('closingOption') ? $request->closingOption : false,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new EmbededWidgetResource($result)
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
    public function show(Request $request, $workspaceId, $shortLinkId, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_embededWidget->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        // getting Short-link in workspace
        $ShortLink = ShortLink::where('uniqueId', $shortLinkId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

        if (!$ShortLink) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No Short-link found!']
            ]);
        }

        try {
            $item = EmbededWidget::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new EmbededWidgetResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Not found!']
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
    public function update(Request $request, $workspaceId, $shortLinkId, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_embededWidget->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        // getting Short-link in workspace
        $ShortLink = ShortLink::where('uniqueId', $shortLinkId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

        if (!$ShortLink) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No Short-link found!']
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:250',
            'canCodeJs' => 'nullable|boolean',
            'canCodeHtml' => 'nullable|boolean',
            'jsCode' => 'nullable|string|max:1000',
            'HTMLCode' => 'nullable|string|max:1000',
            'displayAt' => 'nullable|string',
            'delay' => 'nullable|integer',
            'position' => 'nullable|string',
            'animation' => 'nullable|boolean',
            'closingOption' => 'nullable|boolean',

            'isActive' => 'nullable|boolean',
            'sortOrderNo' => 'nullable|integer',
            'extraAttributes' => 'nullable|json'
        ]);

        try {
            $item = EmbededWidget::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('shortLinkId', $ShortLink->id)->first();

            if ($item) {
                $item->update([
                    'name' => $request->has('name') ? $request->name : $item->name,
                    'canCodeJs' => $request->has('canCodeJs') ? $request->canCodeJs : $item->canCodeJs,
                    'canCodeHtml' => $request->has('canCodeHtml') ? $request->canCodeHtml : $item->canCodeHtml,
                    'jsCode' => $request->has('jsCode') ? $request->jsCode : $item->jsCode,
                    'HTMLCode' => $request->has('HTMLCode') ? $request->HTMLCode : $item->HTMLCode,
                    'displayAt' => $request->has('displayAt') ? $request->displayAt : $item->displayAt,
                    'delay' => $request->has('delay') ? $request->delay : $item->delay,
                    'position' => $request->has('position') ? $request->position : $item->position,
                    'animation' => $request->has('animation') ? $request->animation : $item->animation,
                    'closingOption' => $request->has('closingOption') ? $request->closingOption : $item->closingOption,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $request->sortOrderNo,
                    'isActive' => $request->has('isActive') ? $request->isActive : $request->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $request->extraAttributes,
                ]);

                $item = EmbededWidget::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new EmbededWidgetResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Not found!']
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
    public function destroy(Request $request, $workspaceId, $shortLinkId, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_embededWidget->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        // getting Short-link in workspace
        $ShortLink = ShortLink::where('uniqueId', $shortLinkId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

        if (!$ShortLink) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No Short-link found!']
            ]);
        }

        try {
            $item = EmbededWidget::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse([]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
