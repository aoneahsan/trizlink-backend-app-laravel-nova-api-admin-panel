<?php

namespace App\Http\Controllers\Zaions\ZLink\LinkInBios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\LinkInBios\LinkInBioResource;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\LinkInBios\LinkInBio;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LinkInBioController extends Controller
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

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No workspace found!']
                ]);
            }

            $itemsCount = LinkInBio::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->count();
            // $items = LinkInBio::where('userId', $userId)->where('workspaceId', $workspace->id)->with('blocks')->get();

            $items = LinkInBio::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->get();

            // return response()->json(['$items' => $items]);

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => LinkInBioResource::collection($items),
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }

        $request->validate([
            'linkInBioTitle' => 'required|string',
            'featureImg' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'pixelIds' => 'nullable|json',
            'utmTagInfo' => 'nullable|json',
            'shortUrl' => 'nullable|json',
            'folderId' => 'nullable|integer',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'abTestingRotatorLinks' => 'nullable|json',
            'geoLocationRotatorLinks' => 'nullable|json',
            'linkExpirationInfo' => 'nullable|json',
            'password' => 'nullable|json',
            'favicon' => 'nullable|string',
            'theme' => 'nullable|json',
            'settings' => 'nullable|json',
            'poweredBy' => 'nullable|json',
            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $result = LinkInBio::create([
                'uniqueId' => uniqid(),
                'userId' => $currentUser->id,
                'workspaceId' => $workspace->id,

                'linkInBioTitle' => $request->has('linkInBioTitle') ? $request->linkInBioTitle : null,
                'featureImg' => $request->has('featureImg') ? $request->featureImg : null,
                'title' => $request->has('title') ? $request->title : null,
                'description' => $request->has('description') ? $request->description : null,
                'pixelIds' => $request->has('pixelIds') ? ZHelpers::zJsonDecode($request->pixelIds) : null,
                'utmTagInfo' => $request->has('utmTagInfo') ? ZHelpers::zJsonDecode($request->utmTagInfo) : null,
                'shortUrl' => $request->has('shortUrl') ? ZHelpers::zJsonDecode($request->shortUrl) : null,
                'folderId' => $request->has('folderId') ? $request->folderId : null,
                'notes' => $request->has('notes') ? $request->notes : null,
                'tags' => $request->has('tags') ? $request->tags : null,
                'abTestingRotatorLinks' => $request->has('abTestingRotatorLinks') ? ZHelpers::zJsonDecode($request->abTestingRotatorLinks) : null,
                'geoLocationRotatorLinks' => $request->has('geoLocationRotatorLinks') ? ZHelpers::zJsonDecode($request->geoLocationRotatorLinks) : null,
                'linkExpirationInfo' => $request->has('linkExpirationInfo') ? ZHelpers::zJsonDecode($request->linkExpirationInfo) : null,
                'password' => $request->has('password') ? ZHelpers::zJsonDecode($request->password) : null,
                'favicon' => $request->has('favicon') ? $request->favicon : null,

                'theme' => $request->has('theme') ?  ZHelpers::zJsonDecode($request->theme) : null,
                'settings' => $request->has('settings') ? ZHelpers::zJsonDecode($request->settings) : null,
                'poweredBy' => $request->has('poweredBy') ? ZHelpers::zJsonDecode($request->poweredBy) : null,
                'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LinkInBioResource($result)
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }

        try {
            $item = LinkInBio::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LinkInBioResource($item)
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }


        $request->validate([
            'linkInBioTitle' => 'required|string',
            'featureImg' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'pixelIds' => 'nullable|json',
            'utmTagInfo' => 'nullable|json',
            'shortUrl' => 'nullable|json',
            'folderId' => 'nullable|integer',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'abTestingRotatorLinks' => 'nullable|json',
            'geoLocationRotatorLinks' => 'nullable|json',
            'linkExpirationInfo' => 'nullable|json',
            'password' => 'nullable|json',
            'favicon' => 'nullable|string',
            'theme' => 'nullable|json',
            'settings' => 'nullable|json',
            'poweredBy' => 'nullable|json',
            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $item = LinkInBio::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->update([
                    'linkInBioTitle' => $request->has('linkInBioTitle') ? $request->linkInBioTitle : $request->linkInBioTitle,
                    'featureImg' => $request->has('featureImg') ? $request->featureImg : $request->featureImg,
                    'title' => $request->has('title') ? $request->title : $request->title,
                    'description' => $request->has('description') ? $request->description : $request->description,
                    'pixelIds' => $request->has('pixelIds') ? ZHelpers::zJsonDecode($request->pixelIds) : $request->pixelIds,
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

                    'theme' => $request->has('theme') ?  ZHelpers::zJsonDecode($request->theme) : $request->theme,
                    'settings' => $request->has('settings') ? ZHelpers::zJsonDecode($request->settings) : $request->settings,
                    'poweredBy' => $request->has('poweredBy') ? ZHelpers::zJsonDecode($request->poweredBy) : $request->poweredBy,
                    'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : $request->extraAttributes,
                    'isActive' => $request->has('isActive') ? $request->isActive : $request->isActive,
                ]);

                $item = LinkInBio::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LinkInBioResource($item)
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        if (!$workspace) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No workspace found!']
            ]);
        }

        try {
            $item = LinkInBio::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse(['item' => ['success' => true]]);
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
