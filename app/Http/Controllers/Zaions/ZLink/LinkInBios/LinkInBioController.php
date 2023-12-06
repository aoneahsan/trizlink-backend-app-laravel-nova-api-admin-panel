<?php

namespace App\Http\Controllers\Zaions\ZLink\LinkInBios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\LinkInBios\LinkInBioResource;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Models\ZLink\LinkInBios\LinkInBio;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Enums\WSEnum;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
use App\Zaions\Enums\WSPermissionsEnum;
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
    public function index(Request $request, $type, $uniqueId)
    {
        try {
            $currentUser = $request->user();

            switch ($type) {
                case WSEnum::shareWorkspace->value:
                    // first getting the member from member we will get share workspace
                    $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                    if (!$member) {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Share workspace not found!']
                        ]);
                    }

                    Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                    // $member->inviterId => id of owner of the workspace
                    $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Share workspace not found!']
                        ]);
                    }

                    $itemsCount = LinkInBio::where('workspaceId', $workspace->id)->count();
                    $items = LinkInBio::where('workspaceId', $workspace->id)->get();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'items' => LinkInBioResource::collection($items),
                        'itemsCount' => $itemsCount
                    ]);
                    break;

                default:
                    Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_linkInBio->name));

                    $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Workspace not found!']
                        ]);
                    }

                    $itemsCount = LinkInBio::where('workspaceId', $workspace->id)->count();
                    $items = LinkInBio::where('workspaceId', $workspace->id)->get();


                    return ZHelpers::sendBackRequestCompletedResponse([
                        'items' => LinkInBioResource::collection($items),
                        'itemsCount' => $itemsCount
                    ]);
                    break;
            }
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
    public function store(Request $request, $type, $uniqueId)
    {
        try {
            $currentUser = $request->user();

            $validate = [
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
            ];

            switch ($type) {
                case WSEnum::shareWorkspace->value:
                    // first getting the member from member we will get share workspace
                    $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                    if (!$member) {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Share workspace not found!']
                        ]);
                    }

                    Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::create_sws_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                    // $member->inviterId => id of owner of the workspace
                    $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Share workspace not found!']
                        ]);
                    }

                    $request->validate($validate);

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
                    break;

                default:
                    Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_linkInBio->name));

                    $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Workspace not found!']
                        ]);
                    }

                    $request->validate($validate);

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
                    break;
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
    public function show(Request $request, $type, $uniqueId, $itemId)
    {
        try {
            $currentUser = $request->user();

            switch ($type) {
                case WSEnum::shareWorkspace->value:
                    // first getting the member from member we will get share workspace
                    $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                    if (!$member) {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Share workspace not found!']
                        ]);
                    }

                    Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::view_sws_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                    // $member->inviterId => id of owner of the workspace
                    $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Share workspace not found!']
                        ]);
                    }

                    $item = LinkInBio::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                    if ($item) {
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new LinkInBioResource($item)
                        ]);
                    } else {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Link-in-bio not found!']
                        ]);
                    }
                    break;

                default:
                    Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_linkInBio->name));

                    $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Workspace not found!']
                        ]);
                    }

                    $item = LinkInBio::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                    if ($item) {
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new LinkInBioResource($item)
                        ]);
                    } else {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Link-in-bio not found!']
                        ]);
                    }
                    break;
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
    public function update(Request $request, $type, $uniqueId, $itemId)
    {
        try {
            $currentUser = $request->user();

            $validate = [
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
            ];

            switch ($type) {
                case WSEnum::shareWorkspace->value:
                    // first getting the member from member we will get share workspace
                    $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                    if (!$member) {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Share workspace not found!']
                        ]);
                    }

                    Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                    // $member->inviterId => id of owner of the workspace
                    $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Share workspace not found!']
                        ]);
                    }

                    $request->validate($validate);

                    $item = LinkInBio::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                    if ($item) {
                        $item->update([
                            'linkInBioTitle' => $request->has('linkInBioTitle') ? $request->linkInBioTitle : $item->linkInBioTitle,
                            'featureImg' => $request->has('featureImg') ? $request->featureImg : $item->featureImg,
                            'title' => $request->has('title') ? $request->title : $item->title,
                            'description' => $request->has('description') ? $request->description : $item->description,
                            'pixelIds' => $request->has('pixelIds') ? ZHelpers::zJsonDecode($request->pixelIds) : $item->pixelIds,
                            'utmTagInfo' => $request->has('utmTagInfo') ? ZHelpers::zJsonDecode($request->utmTagInfo) : $item->utmTagInfo,
                            'shortUrl' => $request->has('shortUrl') ? ZHelpers::zJsonDecode($request->shortUrl) : $item->shortUrl,
                            'folderId' => $request->has('folderId') ? $request->folderId : $item->folderId,
                            'notes' => $request->has('notes') ? $request->notes : $item->notes,
                            'tags' => $request->has('tags') ? $request->tags : $item->tags,
                            'abTestingRotatorLinks' => $request->has('abTestingRotatorLinks') ? ZHelpers::zJsonDecode($request->abTestingRotatorLinks) : $item->abTestingRotatorLinks,
                            'geoLocationRotatorLinks' => $request->has('geoLocationRotatorLinks') ? ZHelpers::zJsonDecode($request->geoLocationRotatorLinks) : $item->geoLocationRotatorLinks,
                            'linkExpirationInfo' => $request->has('linkExpirationInfo') ? ZHelpers::zJsonDecode($request->linkExpirationInfo) : $item->linkExpirationInfo,
                            'password' => $request->has('password') ? ZHelpers::zJsonDecode($request->password) : $item->password,
                            'favicon' => $request->has('favicon') ? $request->favicon : $item->favicon,

                            'theme' => $request->has('theme') ?  ZHelpers::zJsonDecode($request->theme) : $item->theme,
                            'settings' => $request->has('settings') ? ZHelpers::zJsonDecode($request->settings) : $item->settings,
                            'poweredBy' => $request->has('poweredBy') ? ZHelpers::zJsonDecode($request->poweredBy) : $item->poweredBy,
                            'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : $item->extraAttributes,
                            'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                        ]);

                        $item = LinkInBio::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new LinkInBioResource($item)
                        ]);
                    } else {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Link-in-bio not found!']
                        ]);
                    }
                    break;

                default:
                    Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_linkInBio->name));

                    $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Workspace not found!']
                        ]);
                    }

                    $request->validate($validate);

                    $item = LinkInBio::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                    if ($item) {
                        $item->update([
                            'linkInBioTitle' => $request->has('linkInBioTitle') ? $request->linkInBioTitle : $item->linkInBioTitle,
                            'featureImg' => $request->has('featureImg') ? $request->featureImg : $item->featureImg,
                            'title' => $request->has('title') ? $request->title : $item->title,
                            'description' => $request->has('description') ? $request->description : $item->description,
                            'pixelIds' => $request->has('pixelIds') ? ZHelpers::zJsonDecode($request->pixelIds) : $item->pixelIds,
                            'utmTagInfo' => $request->has('utmTagInfo') ? ZHelpers::zJsonDecode($request->utmTagInfo) : $item->utmTagInfo,
                            'shortUrl' => $request->has('shortUrl') ? ZHelpers::zJsonDecode($request->shortUrl) : $item->shortUrl,
                            'folderId' => $request->has('folderId') ? $request->folderId : $item->folderId,
                            'notes' => $request->has('notes') ? $request->notes : $item->notes,
                            'tags' => $request->has('tags') ? $request->tags : $item->tags,
                            'abTestingRotatorLinks' => $request->has('abTestingRotatorLinks') ? ZHelpers::zJsonDecode($request->abTestingRotatorLinks) : $item->abTestingRotatorLinks,
                            'geoLocationRotatorLinks' => $request->has('geoLocationRotatorLinks') ? ZHelpers::zJsonDecode($request->geoLocationRotatorLinks) : $item->geoLocationRotatorLinks,
                            'linkExpirationInfo' => $request->has('linkExpirationInfo') ? ZHelpers::zJsonDecode($request->linkExpirationInfo) : $item->linkExpirationInfo,
                            'password' => $request->has('password') ? ZHelpers::zJsonDecode($request->password) : $item->password,
                            'favicon' => $request->has('favicon') ? $request->favicon : $item->favicon,

                            'theme' => $request->has('theme') ?  ZHelpers::zJsonDecode($request->theme) : $item->theme,
                            'settings' => $request->has('settings') ? ZHelpers::zJsonDecode($request->settings) : $item->settings,
                            'poweredBy' => $request->has('poweredBy') ? ZHelpers::zJsonDecode($request->poweredBy) : $item->poweredBy,
                            'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : $item->extraAttributes,
                            'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                        ]);

                        $item = LinkInBio::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new LinkInBioResource($item)
                        ]);
                    } else {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Link-in-bio not found!']
                        ]);
                    }
                    break;
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
    public function destroy(Request $request, $type, $uniqueId, $itemId)
    {
        try {
            $currentUser = $request->user();

            switch ($type) {
                case WSEnum::shareWorkspace->value:
                    // first getting the member from member we will get share workspace
                    $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                    if (!$member) {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Share workspace not found!']
                        ]);
                    }

                    Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::delete_sws_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                    // $member->inviterId => id of owner of the workspace
                    $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Share workspace not found!']
                        ]);
                    }

                    $item = LinkInBio::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                    if ($item) {
                        $item->forceDelete();
                        return ZHelpers::sendBackRequestCompletedResponse(['item' => ['success' => true]]);
                    } else {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Link-in-bio not found!']
                        ]);
                    }
                    break;

                default:
                    Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                    $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Workspace not found!']
                        ]);
                    }

                    $item = LinkInBio::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                    if ($item) {
                        $item->forceDelete();
                        return ZHelpers::sendBackRequestCompletedResponse(['item' => ['success' => true]]);
                    } else {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Link-in-bio not found!']
                        ]);
                    }
                    break;
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
