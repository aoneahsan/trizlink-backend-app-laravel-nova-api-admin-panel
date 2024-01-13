<?php

namespace App\Http\Controllers\Zaions\ZLink\LinkInBios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\LinkInBios\LinkInBioResource;
use App\Models\Default\UserSetting;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Models\ZLink\LinkInBios\LinkInBio;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\PlanFeatures;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Enums\WSEnum;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
use App\Zaions\Enums\WSPermissionsEnum;
use App\Zaions\Helpers\ZAccountHelpers;
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

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_linkInBio->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to viewAny link-in-bio.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

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

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_linkInBio->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to create link-in-bio.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::create_sws_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $itemsCount = LinkInBio::where('workspaceId', $workspace->id)->count();
            $linkInBioFoldersLimit = ZAccountHelpers::WorkspaceServicesLimits($workspace, PlanFeatures::linkInBio->value, $itemsCount);

            if($linkInBioFoldersLimit === true){
                $validate = [
                    'linkInBioTitle' => 'required|string',
                    'featureImg' => 'nullable|json',
                    'title' => 'nullable|string',
                    'description' => 'nullable|string',
                    'pixelIds' => 'nullable|json',
                    'utmTagInfo' => 'nullable|json',
                    'shortUrl' => 'nullable|json',
                    'shortUrlDomain' => 'required|string|max:250',
                    'folderId' => 'nullable|string',
                    'notes' => 'nullable|string',
                    'tags' => 'nullable|json',
                    'abTestingRotatorLinks' => 'nullable|json',
                    'geoLocationRotatorLinks' => 'nullable|json',
                    'linkExpirationInfo' => 'nullable|json',
                    'password' => 'nullable|json',
                    'favicon' => 'nullable|json',
                    'theme' => 'nullable|json',
                    'settings' => 'nullable|json',
                    'poweredBy' => 'nullable|json',
                    'sortOrderNo' => 'nullable|integer',
                    'isActive' => 'nullable|boolean',
                    'extraAttributes' => 'nullable|json',
                ];
    
                $request->validate($validate);
    
                $shortLinkUrlPath = '';
    
                do {
                    $generatedShortUrlPath = ZHelpers::zGenerateRandomString();
                    $checkShortUrlPath = LinkInBio::where('shortUrlPath', $generatedShortUrlPath)->exists();
    
                    $shortLinkUrlPath = $generatedShortUrlPath;
                } while ($checkShortUrlPath);
    
                $result = LinkInBio::create([
                    'uniqueId' => uniqid(),
                    'createdBy' => $currentUser->id,
                    'workspaceId' => $workspace->id,
    
                    'shortUrlDomain' => $request->has('shortUrlDomain') ? $request->shortUrlDomain : null,
                    'shortUrlPath' => $shortLinkUrlPath,
                    'linkInBioTitle' => $request->has('linkInBioTitle') ? $request->linkInBioTitle : null,
                    'featureImg' => $request->has('featureImg') ?  ZHelpers::zJsonDecode($request->featureImg) : null,
                    'title' => $request->has('title') ? $request->title : null,
                    'description' => $request->has('description') ? $request->description : null,
                    'pixelIds' => $request->has('pixelIds') ? ZHelpers::zJsonDecode($request->pixelIds) : null,
                    'utmTagInfo' => $request->has('utmTagInfo') ? ZHelpers::zJsonDecode($request->utmTagInfo) : null,
                    'shortUrl' => $request->has('shortUrl') ? ZHelpers::zJsonDecode($request->shortUrl) : null,
                    'folderId' => $request->has('folderId') ? $request->folderId : null,
                    'notes' => $request->has('notes') ? $request->notes : null,
                    'tags' => $request->has('tags') ? ZHelpers::zJsonDecode($request->tags) : null,
                    'abTestingRotatorLinks' => $request->has('abTestingRotatorLinks') ? ZHelpers::zJsonDecode($request->abTestingRotatorLinks) : null,
                    'geoLocationRotatorLinks' => $request->has('geoLocationRotatorLinks') ? ZHelpers::zJsonDecode($request->geoLocationRotatorLinks) : null,
                    'linkExpirationInfo' => $request->has('linkExpirationInfo') ? ZHelpers::zJsonDecode($request->linkExpirationInfo) : null,
                    'password' => $request->has('password') ? ZHelpers::zJsonDecode($request->password) : null,
                    'favicon' => $request->has('favicon') ? ZHelpers::zJsonDecode($request->favicon) : null,
    
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
            } else {
                return ZHelpers::sendBackInvalidParamsResponse([
                    'item' => ['You have reached the limit of link-in-bio\'s you can create.']
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
    public function show(Request $request, $type, $uniqueId, $itemId)
    {
        try {
            $currentUser = $request->user();

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_linkInBio->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to view link-in-bio.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::view_sws_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

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

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_linkInBio->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to update any link in bio.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $validate = [
                'linkInBioTitle' => 'required|string',
                'featureImg' => 'nullable|json',
                'title' => 'nullable|string',
                'description' => 'nullable|string',
                'pixelIds' => 'nullable|json',
                'utmTagInfo' => 'nullable|json',
                'shortUrl' => 'nullable|json',
                'folderId' => 'nullable|string',
                'notes' => 'nullable|string',
                'tags' => 'nullable|string',
                'abTestingRotatorLinks' => 'nullable|json',
                'geoLocationRotatorLinks' => 'nullable|json',
                'linkExpirationInfo' => 'nullable|json',
                'password' => 'nullable|json',
                'favicon' => 'nullable|json',
                'theme' => 'nullable|json',
                'settings' => 'nullable|json',
                'poweredBy' => 'nullable|json',
                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ];

            $request->validate($validate);

            $item = LinkInBio::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->update([
                    'linkInBioTitle' => $request->has('linkInBioTitle') ? $request->linkInBioTitle : $item->linkInBioTitle,
                    'featureImg' => $request->has('featureImg') ?  ZHelpers::zJsonDecode($request->featureImg) : $item->featureImg,
                    'title' => $request->has('title') ? $request->title : $item->title,
                    'description' => $request->has('description') ? $request->description : $item->description,
                    'pixelIds' => $request->has('pixelIds') ? ZHelpers::zJsonDecode($request->pixelIds) : $item->pixelIds,
                    'utmTagInfo' => $request->has('utmTagInfo') ? ZHelpers::zJsonDecode($request->utmTagInfo) : $item->utmTagInfo,
                    'shortUrl' => $request->has('shortUrl') ? ZHelpers::zJsonDecode($request->shortUrl) : $item->shortUrl,
                    'folderId' => $request->has('folderId') ? $request->folderId : $item->folderId,
                    'notes' => $request->has('notes') ? $request->notes : $item->notes,
                    'tags' => $request->has('tags') ? ZHelpers::zJsonDecode($request->tags) : $item->tags,
                    'abTestingRotatorLinks' => $request->has('abTestingRotatorLinks') ? ZHelpers::zJsonDecode($request->abTestingRotatorLinks) : $item->abTestingRotatorLinks,
                    'geoLocationRotatorLinks' => $request->has('geoLocationRotatorLinks') ? ZHelpers::zJsonDecode($request->geoLocationRotatorLinks) : $item->geoLocationRotatorLinks,
                    'linkExpirationInfo' => $request->has('linkExpirationInfo') ? ZHelpers::zJsonDecode($request->linkExpirationInfo) : $item->linkExpirationInfo,
                    'password' => $request->has('password') ? ZHelpers::zJsonDecode($request->password) : $item->password,
                    'favicon' => $request->has('favicon') ? ZHelpers::zJsonDecode($request->favicon) : $item->favicon,

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
            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_linkInBio->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to delete link-in-bio.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::delete_sws_linkInBio->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

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
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
