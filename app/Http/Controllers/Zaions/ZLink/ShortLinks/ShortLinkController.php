<?php

namespace App\Http\Controllers\Zaions\ZLink\ShortLinks;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\ShortLinks\ShortLinkResource;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\ShortLinks\ShortLink;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ShortLinkController extends Controller
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

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No workspace found!']
                ]);
            }

            $itemsCount = ShortLink::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->count();
            $items = ShortLink::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => ShortLinkResource::collection($items),
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }

        $request->validate([
            'type' => 'required|string|max:250',
            'target' => 'required|json',
            'title' => 'required|string|max:250',
            'featureImg' => 'nullable|string|max:250',
            'description' => 'nullable|string|max:1000',
            'pixelIds' => 'nullable|string|max:250',
            'utmTagInfo' => 'nullable|json',
            'shortUrl' => 'nullable|json',
            'folderId' => 'nullable|string',
            'notes' => 'nullable|string|max:250',
            'tags' => 'nullable|string|max:250',
            'abTestingRotatorLinks' => 'nullable|json',
            'geoLocationRotatorLinks' => 'nullable|json',
            'linkExpirationInfo' => 'nullable|json',
            'password' => 'nullable|json',
            'favicon' => 'nullable|string',
            'isFavorite' => 'nullable|boolean',
            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $result = ShortLink::create([
                'uniqueId' => uniqid(),
                'userId' => $currentUser->id,
                'workspaceId' => $workspace->id,

                'type' => $request->has('type') ? $request->type : null,
                'target' => $request->has('target') ? ZHelpers::zJsonDecode($request->target) : null,
                'title' => $request->has('title') ? $request->title : null,
                'featureImg' => $request->has('featureImg') ? $request->featureImg : null,
                'description' => $request->has('description') ? $request->description : null,
                'pixelIds' => $request->has('pixelIds') ? $request->pixelIds : null,
                'utmTagInfo' => $request->has('utmTagInfo') ? ZHelpers::zJsonDecode($request->utmTagInfo) : null,
                'shortUrl' =>  $request->has('shortUrl') ? ZHelpers::zJsonDecode($request->shortUrl) : null,
                'folderId' => $request->has('folderId') ? $request->folderId : null,
                'notes' => $request->has('notes') ? $request->notes : null,
                'tags' => $request->has('tags') ? $request->tags : null,
                'abTestingRotatorLinks' => $request->has('abTestingRotatorLinks') ? ZHelpers::zJsonDecode($request->abTestingRotatorLinks) : null,
                'geoLocationRotatorLinks' => $request->has('geoLocationRotatorLinks') ? ZHelpers::zJsonDecode($request->geoLocationRotatorLinks) : null,
                'linkExpirationInfo' => $request->has('linkExpirationInfo') ? ZHelpers::zJsonDecode($request->linkExpirationInfo) : null,
                'password' => $request->has('password') ? ZHelpers::zJsonDecode($request->password) : null,
                'favicon' => $request->has('favicon') ? $request->favicon : null,
                'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : false,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'extraAttributes' =>  $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new ShortLinkResource($result)
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }

        try {
            $item = ShortLink::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new ShortLinkResource($item)
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
    public function update(Request $request, $workspaceId, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }

        $request->validate([
            'type' => 'required|string|max:250',
            'target' => 'required|json',
            'title' => 'required|string|max:250',
            'featureImg' => 'nullable|string|max:250',
            'description' => 'nullable|string|max:1000',
            'pixelIds' => 'nullable|string|max:250',
            'utmTagInfo' => 'nullable|json',
            'shortUrl' => 'nullable|json',
            'folderId' => 'nullable|string',
            'notes' => 'nullable|string|max:250',
            'tags' => 'nullable|string|max:250',
            'abTestingRotatorLinks' => 'nullable|json',
            'geoLocationRotatorLinks' => 'nullable|json',
            'linkExpirationInfo' => 'nullable|json',
            'password' => 'nullable|json',
            'favicon' => 'nullable|string',
            'isFavorite' => 'nullable|boolean',

            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $item = ShortLink::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->update([
                    'type' => $request->has('type') ? $request->type : $request->type,
                    'target' => $request->has('target') ? ZHelpers::zJsonDecode($request->target) : $request->target,
                    'title' => $request->has('title') ? $request->title : $request->title,
                    'featureImg' => $request->has('featureImg') ? $request->featureImg : $request->featureImg,
                    'description' => $request->has('description') ? $request->description : $request->description,
                    'pixelIds' => $request->has('pixelIds') ? $request->pixelIds : $request->pixelIds,
                    'utmTagInfo' => $request->has('utmTagInfo') ? ZHelpers::zJsonDecode($request->utmTagInfo) : $request->utmTagInfo,
                    'shortUrl' => $request->has('shortUrl') ? ZHelpers::zJsonDecode($request->shortUrl) : $request->shortUrl,
                    'folderId' => $request->has('folderId') ? $request->folderId : $request->folderId,
                    'notes' => $request->has('notes') ? $request->notes : $request->notes,
                    'tags' => $request->has('tags') ? $request->tags : $request->tags,
                    'abTestingRotatorLinks' => $request->has('abTestingRotatorLinks') ? ZHelpers::zJsonDecode($request->abTestingRotatorLinks) : $request->abTestingRotatorLinks,
                    'geoLocationRotatorLinks' => $request->has('geoLocationRotatorLinks') ? ZHelpers::zJsonDecode($request->geoLocationRotatorLinks) : $request->geoLocationRotatorLinks,
                    'linkExpirationInfo' => $request->has('linkExpirationInfo') ? ZHelpers::zJsonDecode($request->linkExpirationInfo) : $request->linkExpirationInfo,
                    'password' => $request->has('password') ? ZHelpers::zJsonDecode($request->password) : $request->password,
                    'favicon' => $request->has('favicon') ? $request->favicon : $request->favicon,
                    'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : false,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo :
                        $request->sortOrderNo,
                    'isActive' => $request->has('isActive') ? $request->isActive : $request->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : $request->extraAttributes,
                ]);

                $item = ShortLink::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new ShortLinkResource($item)
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
    public function destroy(Request $request, $workspaceId, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }
        try {
            $item = ShortLink::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse(['item' => ['success' => true]]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Short link not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
