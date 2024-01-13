<?php

namespace App\Http\Controllers\Zaions\ZLink\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\Common\FolderResource;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Models\ZLink\Common\Folder;
use App\Zaions\Enums\FolderModalsEnum;
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
use Illuminate\Support\Facades\Hash;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request, $workspaceId)
    // {
    //     try {
    //         $currentUser = $request->user();

    //         Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

    //         // getting workspace
    //         $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

    //         if (!$workspace) {
    //             return ZHelpers::sendBackNotFoundResponse([
    //                 "item" => ['Workspace not found!']
    //             ]);
    //         }

    //         $itemsCount = Folder::where('workspaceId', $workspace->id)->count();
    //         $items = Folder::where('workspaceId', $workspace->id)->orderBy('sortOrderNo', 'asc')->get();

    //         return response()->json([
    //             'success' => true,
    //             'errors' => [],
    //             'message' => 'Request Completed Successfully!',
    //             'data' => [
    //                 'items' => FolderResource::collection($items),
    //                 'itemsCount' => $itemsCount
    //             ],
    //             'status' => 200
    //         ]);
    //     } catch (\Throwable $th) {
    //         return ZHelpers::sendBackServerErrorResponse($th);
    //     }
    // }

    public function index(Request $request, $type, $uniqueId, $modal)
    {
        try {
            $currentUser = $request->user();

            $workspace = null;
            $member = null;
            $isShareWs = false;

            if ($type === WSEnum::shareWorkspace->value) {
                $isShareWs = true;

                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to view any folders.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_folder->name));
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            }

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            # Accounting to modal getting data. shortlink/link-in-bio
            switch ($modal) {
                case FolderModalsEnum::shortlink->value:
                    # Checking if member has permission to view any short-link folders.
                    Gate::allowIf($isShareWs ? $member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_sl_folder->name) : $currentUser->hasPermissionTo(PermissionsEnum::viewAny_sl_folder->name), $isShareWs ? ResponseMessagesEnum::Unauthorized->name : null, $isShareWs ?  ResponseCodesEnum::Unauthorized->name : null);

                    $itemsCount = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::shortlink->name)->count();
                    $items = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::shortlink->name)->orderBy('sortOrderNo', 'asc')->get();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'items' => FolderResource::collection($items),
                        'itemsCount' => $itemsCount
                    ]);
                    break;

                case FolderModalsEnum::linkInBio->value:
                    # Checking if member has permission to view any link-in-bio folders.
                    Gate::allowIf($isShareWs ? $member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_lib_folder->name) : $currentUser->hasPermissionTo(PermissionsEnum::viewAny_lib_folder->name), $isShareWs ? ResponseMessagesEnum::Unauthorized->name : null, $isShareWs ? ResponseCodesEnum::Unauthorized->name : null);

                    $itemsCount = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::linkInBio->name)->count();
                    $items = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::linkInBio->name)->orderBy('sortOrderNo', 'asc')->get();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'items' => FolderResource::collection($items),
                        'itemsCount' => $itemsCount
                    ]);
                    break;
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    // Sub function of index() to handle share workspace logic
    // public function shareWorkspaceIndex($currentUser, $uniqueId, $modal)
    // {
    //     try {
    //         # first getting the member from member_table so we can get share workspace
    //         $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

    //         if (!$member) {
    //             return ZHelpers::sendBackNotFoundResponse([
    //                 'item' => ['Share workspace not found!']
    //             ]);
    //         }

    //         # First of all checking if member has permission to view any folders.
    //         Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

    //         # $member->inviterId => id of owner of the workspace
    //         $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

    //         if (!$workspace) {
    //             return ZHelpers::sendBackNotFoundResponse([
    //                 "item" => ['Workspace not found!']
    //             ]);
    //         }

    //         # Accounting to modal getting data. shortlink/link-in-bio
    //         switch ($modal) {
    //             case FolderModalsEnum::shortlink->value:
    //                 # Checking if member has permission to view any short-link folders.
    //                 Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_sl_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

    //                 $itemsCount = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::shortlink->name)->count();
    //                 $items = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::shortlink->name)->orderBy('sortOrderNo', 'asc')->get();

    //                 return ZHelpers::sendBackRequestCompletedResponse([
    //                     'items' => FolderResource::collection($items),
    //                     'itemsCount' => $itemsCount
    //                 ]);
    //                 break;

    //             case FolderModalsEnum::linkInBio->value:
    //                 # Checking if member has permission to view any link-in-bio folders.
    //                 Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_lib_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

    //                 $itemsCount = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::linkInBio->name)->count();
    //                 $items = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::linkInBio->name)->orderBy('sortOrderNo', 'asc')->get();

    //                 return ZHelpers::sendBackRequestCompletedResponse([
    //                     'items' => FolderResource::collection($items),
    //                     'itemsCount' => $itemsCount
    //                 ]);
    //                 break;
    //         }
    //     } catch (\Throwable $th) {
    //         return ZHelpers::sendBackServerErrorResponse($th);
    //     }
    // }

    // // Sub function of index() to handle personal workspace logic
    // public function personalWorkspaceIndex($currentUser, $uniqueId, $modal)
    // {
    //     try {
    //         # First of all checking if user has permission to view any folders.
    //         Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_folder->name));

    //         # getting workspace
    //         $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();

    //         # workspace exist check
    //         if (!$workspace) {
    //             return ZHelpers::sendBackNotFoundResponse([
    //                 "item" => ['Workspace not found!']
    //             ]);
    //         }

    //         # Accounting to modal getting data. shortlink/link-in-bio
    //         switch ($modal) {
    //                 # Short link
    //             case FolderModalsEnum::shortlink->value:
    //                 # Checking if user has permission to view any short-link folders.
    //                 Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_sl_folder->name));

    //                 // TODO: use withCount instead of a separate query where we have same fetch logic for both, count and get request.
    //                 # Getting short link folders
    //                 $itemsCount = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::shortlink->name)->count();
    //                 $items = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::shortlink->name)->orderBy('sortOrderNo', 'asc')->withCount()->get();

    //                 return ZHelpers::sendBackRequestCompletedResponse([
    //                     'items' => FolderResource::collection($items),
    //                     'itemsCount' => $itemsCount
    //                 ]);
    //                 break;

    //                 # Link in bio
    //             case FolderModalsEnum::linkInBio->value:
    //                 # Checking if user has permission to view any link-in-bio folders.
    //                 Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_lib_folder->name));

    //                 # Getting link in bio folders
    //                 $itemsCount = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::linkInBio->name)->count();
    //                 $items = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::linkInBio->name)->orderBy('sortOrderNo', 'asc')->get();

    //                 return ZHelpers::sendBackRequestCompletedResponse([
    //                     'items' => FolderResource::collection($items),
    //                     'itemsCount' => $itemsCount
    //                 ]);
    //                 break;
    //         }
    //     } catch (\Throwable $th) {
    //         return ZHelpers::sendBackServerErrorResponse($th);
    //     }
    // }

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
            $member = null;
            $isShareWs = false;

            if ($type === WSEnum::shareWorkspace->value) {
                $isShareWs = true;

                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to view any folders.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::create_sws_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_folder->name));
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $request->validate([
                'title' => 'required|string|max:250',
                'icon' => 'nullable|string|max:250',
                'isStared' => 'nullable|boolean',
                'isHidden' => 'nullable|boolean',
                'isFavorite' => 'nullable|boolean',
                'folderForModel' => 'nullable|string',
                'isPasswordProtected' => 'nullable|boolean',
                'password' => 'nullable|string|max:250',
                'isDefault' => 'nullable|boolean',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            # Accounting to modal getting data. shortlink/link-in-bio
            switch ($request->folderForModel) {
                case FolderModalsEnum::shortlink->value:
                    $itemsCount = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::shortlink->name)->count();
                    $shortLinkFoldersLimit = ZAccountHelpers::WorkspaceServicesLimits($workspace, PlanFeatures::shortLinksFolder->value, $itemsCount);

                    if ($shortLinkFoldersLimit === true) {
                        # Checking if member has permission to view any short-link folders.
                        Gate::allowIf($isShareWs ? $member->memberRole->hasPermissionTo(WSPermissionsEnum::create_sws_sl_folder->name) : $currentUser->hasPermissionTo(PermissionsEnum::create_sl_folder->name), $isShareWs ? ResponseMessagesEnum::Unauthorized->name : null, $isShareWs ?  ResponseCodesEnum::Unauthorized->name : null);

                        return $this->createFolder($request, $currentUser->id, $workspace->id);
                    } else {
                        return ZHelpers::sendBackInvalidParamsResponse([
                            'item' => ['You have reached the limit of short links folders you can create.']
                        ]);
                    }
                    break;

                case FolderModalsEnum::linkInBio->value:
                    $itemsCount = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::linkInBio->name)->count();
                    $linkInBioFoldersLimit = ZAccountHelpers::WorkspaceServicesLimits($workspace, PlanFeatures::linksInBioFolder->value, $itemsCount);

                    if ($linkInBioFoldersLimit === true) {
                        # Checking if member has permission to view any link-in-bio folders.
                    Gate::allowIf($isShareWs ? $member->memberRole->hasPermissionTo(WSPermissionsEnum::create_sws_lib_folder->name) : $currentUser->hasPermissionTo(PermissionsEnum::create_lib_folder->name), $isShareWs ? ResponseMessagesEnum::Unauthorized->name : null, $isShareWs ? ResponseCodesEnum::Unauthorized->name : null);

                    return $this->createFolder($request, $currentUser->id, $workspace->id);
                    } else {
                        return ZHelpers::sendBackInvalidParamsResponse([
                            'item' => ['You have reached the limit of link in bio folders you can create.']
                        ]);
                    }
                    
                    break;

                default:
                    return ZHelpers::sendBackBadRequestResponse([]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    // Sub function for personalWorkspaceStore(), shareWorkspaceStore()
    function createFolder(Request $request, $currentUserId, $workspaceId)
    {
        try {
            $result = Folder::create([
                'uniqueId' => uniqid(),
                'createdBy' => $currentUserId,
                'workspaceId' => $workspaceId,
                'title' => $request->has('title') ? $request->title : null,
                'icon' => $request->has('icon') ? $request->icon : null,
                'isStared' => $request->has('isStared') ? $request->isStared : false,
                'isHidden' => $request->has('isHidden') ? $request->isHidden : false,
                'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : false,
                'folderForModel' => $request->has('folderForModel') ? $request->folderForModel : false,
                'isPasswordProtected' => $request->has('isPasswordProtected') ? $request->isPasswordProtected : false,
                'password' => $request->has('password') ? Hash::make($request->password) : null,
                'isDefault' => $request->has('isDefault') ? $request->isDefault : null,
                'isActive' => $request->has('isActive') ? $request->isActive : true,
                'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new FolderResource($result)
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
    public function show(Request $request, $type, $uniqueId, $itemId)
    {
        try {
            $currentUser = $request->user();

            switch ($type) {
                case WSEnum::shareWorkspace->value:
                    # first getting the member from member we will get share workspace
                    $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                    if (!$member) {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Share workspace not found!']
                        ]);
                    }

                    Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::view_sws_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                    # $member->inviterId => id of owner of the workspace
                    $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Workspace not found!']
                        ]);
                    }

                    $item = Folder::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                    if ($item) {
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new FolderResource($item)
                        ]);
                    } else {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Folder not found!']
                        ]);
                    }
                    break;

                case WSEnum::personalWorkspace->value:
                    Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                    // getting workspace
                    $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Workspace not found!']
                        ]);
                    }

                    $item = Folder::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                    if ($item) {
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new FolderResource($item)
                        ]);
                    } else {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Folder not found!']
                        ]);
                    }
                    break;

                default:
                    return ZHelpers::sendBackBadRequestResponse([]);
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
            $member = null;
            $isShareWs = false;

            if ($type === WSEnum::shareWorkspace->value) {
                $isShareWs = true;

                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to view any folders.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::create_sws_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_folder->name));
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $request->validate([
                'title' => 'required|string|max:250',
                'icon' => 'nullable|string|max:250',
                'isStared' => 'nullable|boolean',
                'isHidden' => 'nullable|boolean',
                'isFavorite' => 'nullable|boolean',
                'folderForModel' => 'nullable|string',
                'isPasswordProtected' => 'nullable|boolean',
                'password' => 'nullable|string|max:250',
                'isDefault' => 'nullable|boolean',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            if ($request->folderForModel === FolderModalsEnum::shortlink->name) {
                Gate::allowIf($isShareWs ? $member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_sl_folder->name) : $currentUser->hasPermissionTo(PermissionsEnum::update_sl_folder->name), $isShareWs ? ResponseMessagesEnum::Unauthorized->name : null, $isShareWs ?  ResponseCodesEnum::Unauthorized->name : null);

                return $this->updateFolder($request, $workspace->id, $itemId);
            } else if ($request->folderForModel === FolderModalsEnum::linkInBio->name) {
                Gate::allowIf($isShareWs ? $member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_lib_folder->name) : $currentUser->hasPermissionTo(PermissionsEnum::update_lib_folder->name), $isShareWs ? ResponseMessagesEnum::Unauthorized->name : null, $isShareWs ?  ResponseCodesEnum::Unauthorized->name : null);

                return $this->updateFolder($request, $workspace->id, $itemId);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function updateFolder($request, $workspaceId, $itemId)
    {
        try {
            $item = Folder::where('uniqueId', $itemId)->where('workspaceId', $workspaceId)->first();

            if ($item) {
                $item->update([
                    'title' => $request->has('title') ? $request->title : $item->title,
                    'icon' => $request->has('icon') ? $request->icon : $item->icon,
                    'isStared' => $request->has('isStared') ? $request->isStared : $item->isStared,
                    'isHidden' => $request->has('isHidden') ? $request->isHidden : $item->isHidden,
                    'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : $item->isFavorite,
                    'folderForModel' => $request->has('folderForModel') ? $request->folderForModel : $item->folderForModel,
                    'isPasswordProtected' => $request->has('isPasswordProtected') ? $request->isPasswordProtected : $item->isPasswordProtected,
                    'password' => $request->has('password') ? Hash::make($request->password) : $item->password,
                    'isDefault' => $request->has('isDefault') ? $request->isDefault : $item->isDefault,
                    'isDefault' => $request->has('isDefault') ? $request->isDefault : $item->isDefault,
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $item->extraAttributes,
                ]);

                $item = Folder::where('uniqueId', $itemId)->where('workspaceId', $workspaceId)->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new FolderResource($item)
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Folder not found!']
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

            switch ($type) {
                case WSEnum::shareWorkspace->value:
                    # first getting the member from member we will get share workspace
                    $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                    if (!$member) {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Share workspace not found!']
                        ]);
                    }

                    Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::delete_sws_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                    # $member->inviterId => id of owner of the workspace
                    $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Workspace not found!']
                        ]);
                    }

                    $item = Folder::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                    if ($item) {
                        $item->forceDelete();
                        return ZHelpers::sendBackRequestCompletedResponse(['item' => ['success' => true]]);
                    } else {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Folder not found!']
                        ]);
                    }
                    break;

                case WSEnum::personalWorkspace->value:
                    Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                    // getting workspace
                    $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Workspace not found!']
                        ]);
                    }

                    $item = Folder::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                    if ($item) {
                        $item->forceDelete();
                        return ZHelpers::sendBackRequestCompletedResponse(['item' => ['success' => true]]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([
                            'item' => ['Folder not found!']
                        ]);
                    }
                    break;

                default:
                    return ZHelpers::sendBackBadRequestResponse([]);
                    break;
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function getShortLinksFolders(Request $request, $type, $uniqueId)
    {
        try {
            $currentUser = $request->user();

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_folder->name));

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

                # First of all checking if member has permission to view any folders.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

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

            $items = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::shortlink->name)->get();

            return ZHelpers::sendBackRequestCompletedResponse([
                'items' => FolderResource::collection($items),
                'itemsCount' => $items->count()
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function getLinkInBioFolders(Request $request, $type, $uniqueId)
    {
        try {
            $currentUser = $request->user();

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_folder->name));

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

                # First of all checking if member has permission to view any folders.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

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

            $items = Folder::where('workspaceId', $workspace->id)->where('folderForModel', FolderModalsEnum::linkInBio->name)->get();

            return ZHelpers::sendBackRequestCompletedResponse([
                'items' => FolderResource::collection($items),
                'itemsCount' => $items->count()
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function updateSortOrderNo(Request $request, $workspaceId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::sort_folder->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            // getting workspace
            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $request->validate([
                'folders' => 'required' // array of all folders ids ()  {id: string}[]
            ]);

            $folders = $request->input('folders');

            // Loop through the folders and update the sortOrderNo field
            foreach ($folders as $order => $folder) {
                $folder = Folder::where('uniqueId', $folder)->first();
                if ($folder) {
                    $folder->sortOrderNo = $order + 1; // Add 1 to start at 1 instead of 0
                    $folder->save();
                }
            }

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [],
                'status' => 200
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
