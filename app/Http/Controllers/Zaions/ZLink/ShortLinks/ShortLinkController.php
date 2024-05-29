<?php

namespace App\Http\Controllers\Zaions\ZLink\ShortLinks;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\ShortLinks\ShortLinkResource;
use App\Http\Resources\Zaions\ZLink\ShortLinks\SLPublicPageResource;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\ShortLinks\ShortLink;
use App\Models\ZLink\ShortLinks\SLAnalytics;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

use function Spatie\SslCertificate\length;

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
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $itemsCount = ShortLink::where('workspaceId', $workspace->id)->count();
            $items = ShortLink::where('workspaceId', $workspace->id)->get();

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

    public function index2(Request $request, $workspaceId, $pageNumber, $paginationLimit)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $itemsQuery = ShortLink::query()->where('workspaceId', $workspace->id);
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

            // $itemsCount = $itemsQuery->count();

            $itemsCount = ShortLink::where('workspaceId', $workspace->id)->count();
            // $items = ShortLink::where('workspaceId', $workspace->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => ShortLinkResource::collection($itemsQuery),
                    'itemsCount' => $itemsCount,
                    'nextPage' => intval($pageNumber) + 1,
                    'currentPage' => intval($pageNumber) 
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
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $request->validate([
                'type' => 'required|string|max:250',
                'target' => 'required|json',
                'title' => 'required|string|max:65',
                'shortUrlPath' => 'nullable|string|max:6',
                'shortUrlDomain' => 'required|string|max:250',
                'featureImg' => 'nullable|json',
                'description' => 'nullable|string|max:300',
                'pixelIds' => 'nullable|string|max:250',
                'utmTagInfo' => 'nullable|json',
                // 'shortUrl' => 'nullable|json',
                'folderId' => 'nullable|string',
                'notes' => 'nullable|string|max:1000',
                'tags' => 'nullable|json',
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


            $shortLinkUrlPath = $request->shortUrlPath;

            if ($request->has('shortUrlPath') && Str::length($request->shortUrlPath) === 6) {
                $checkShortUrlPath = ShortLink::where('shortUrlPath', $request->shortUrlPath)->first();

                if ($checkShortUrlPath) {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'shortUrlPath' => 'custom path has been taken.'
                    ]);
                }
            } else {
                do {
                    $generatedShortUrlPath = ZHelpers::zGenerateRandomString();
                    $checkShortUrlPath = ShortLink::where('shortUrlPath', $generatedShortUrlPath)->exists();

                    $shortLinkUrlPath = $generatedShortUrlPath;
                } while ($checkShortUrlPath);
            }

            $result = ShortLink::create([
                'uniqueId' => uniqid(),
                'createdBy' => $currentUser->id,
                'workspaceId' => $workspace->id,

                'type' => $request->has('type') ? $request->type : null,
                'target' => $request->has('target') ? ZHelpers::zJsonDecode($request->target) : null,
                'title' => $request->has('title') ? $request->title : null,
                'featureImg' => $request->has('featureImg') ? ZHelpers::zJsonDecode($request->featureImg) : null,
                'description' => $request->has('description') ? $request->description : null,
                'pixelIds' => $request->has('pixelIds') ? $request->pixelIds : null,
                'utmTagInfo' => $request->has('utmTagInfo') ? ZHelpers::zJsonDecode($request->utmTagInfo) : null,
                // 'shortUrl' =>  $request->has('shortUrl') ? ZHelpers::zJsonDecode($request->shortUrl) : null,
                'shortUrlDomain' => $request->has('shortUrlDomain') ? $request->shortUrlDomain : null,
                'shortUrlPath' => $shortLinkUrlPath,
                'folderId' => $request->has('folderId') ? $request->folderId : null,
                'notes' => $request->has('notes') ? $request->notes : null,
                'tags' => $request->has('tags') ? ZHelpers::zJsonDecode($request->tags) : null,
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
            // return ZHelpers::sendBackRequestFailedResponse($th);
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

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $item = ShortLink::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new ShortLinkResource($item)
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Shortlink not found!']
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

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $request->validate([
                'type' => 'required|string|max:250',
                'target' => 'required|json',
                'title' => 'required|string|max:65',
                'featureImg' => 'nullable|json',
                'description' => 'nullable|string|max:300',
                'pixelIds' => 'nullable|string|max:250',
                'utmTagInfo' => 'nullable|json',
                // 'shortUrl' => 'nullable|json',
                'shortUrlDomain' => 'nullable|string',
                'shortUrlPath' => 'nullable|string|max:6',
                'folderId' => 'nullable|string',
                'notes' => 'nullable|string|max:1000',
                'tags' => 'nullable|string',
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

            $item = ShortLink::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->update([
                    'type' => $request->has('type') ? $request->type : $item->type,
                    'target' => $request->has('target') ? ZHelpers::zJsonDecode($request->target) : $item->target,
                    'title' => $request->has('title') ? $request->title : $item->title,
                    'featureImg' => $request->has('featureImg') ? ZHelpers::zJsonDecode($request->featureImg) : $item->featureImg,
                    'description' => $request->has('description') ? $request->description : $item->description,
                    'pixelIds' => $request->has('pixelIds') ? $request->pixelIds : $item->pixelIds,
                    'utmTagInfo' => $request->has('utmTagInfo') ? ZHelpers::zJsonDecode($request->utmTagInfo) : $item->utmTagInfo,
                    'shortUrlDomain' => $request->has('shortUrlDomain') ? $request->shortUrlDomain : $item->shortUrlDomain,
                    'shortUrlPath' => $request->has('shortUrlPath') ? $request->shortUrlPath : $item->shortUrlPath,
                    'folderId' => $request->has('folderId') ? $request->folderId : $item->folderId,
                    'notes' => $request->has('notes') ? $request->notes : $item->notes,
                    'tags' => $request->has('tags') ?  ZHelpers::zJsonDecode($request->tags) : $item->tags,
                    'abTestingRotatorLinks' => $request->has('abTestingRotatorLinks') ? ZHelpers::zJsonDecode($request->abTestingRotatorLinks) : $item->abTestingRotatorLinks,
                    'geoLocationRotatorLinks' => $request->has('geoLocationRotatorLinks') ? ZHelpers::zJsonDecode($request->geoLocationRotatorLinks) : $item->geoLocationRotatorLinks,
                    'linkExpirationInfo' => $request->has('linkExpirationInfo') ? ZHelpers::zJsonDecode($request->linkExpirationInfo) : $item->linkExpirationInfo,
                    'password' => $request->has('password') ? ZHelpers::zJsonDecode($request->password) : $item->password,
                    'favicon' => $request->has('favicon') ? $request->favicon : $item->favicon,
                    'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : $item->isFavorite,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo :
                        $item->sortOrderNo,
                    'isActive' => $request->has('isActive') ? $item->isActive : $request->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : $item->extraAttributes,
                ]);

                $item = ShortLink::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new ShortLinkResource($item)
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Shortlink not found!']
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

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_shortLink->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $item = ShortLink::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse(['item' => ['success' => true]]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Shortlink not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Check if shortUrlPath is available.
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function checkShortUrlPathAvailable(Request $request, $workspaceId, $value)
    {
        try {
            $currentUser = $request->user();

            if ($currentUser) {
                // we are defining short url path length exeat 6 digit.
                if ($value && Str::length($value) === 6) {

                    $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

                    if (!$workspace) {
                        return ZHelpers::sendBackNotFoundResponse([
                            "item" => ['Workspace not found!']
                        ]);
                    }

                    $item = ShortLink::where('shortUrlPath', $value)->first();

                    if ($item) {
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => [
                                'isAvailable' => false,
                                'message' => 'Not available',
                                'value' => $value

                            ]
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => [
                                'isAvailable' => true,
                                'message' => 'Available',
                                'value' => $value
                            ]
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackInvalidParamsResponse([
                        'item' => [
                            'message' => 'value must be exeat to 6'
                        ]
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['User not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    // Public API
    public function getTargetUrlInfo(Request $request, $urlPath)
    {
        try {
            if ($urlPath && Str::length($urlPath) === 6) {
                $item = ShortLink::where('shortUrlPath', $urlPath)->first();

                if ($item) {
                    $request->validate([
                        'type' => 'nullable|string|max:250',
                        'userIP' => 'nullable|string|max:250',
                        'userLocationCoords' => 'nullable|json',
                        'userDeviceInfo' => 'nullable|json',

                        'extraAttributes' => 'nullable|json',
                    ]);
                    // TODO: create separate function (may be in this same file)
                    $result = SLAnalytics::create([
                        'uniqueId' => uniqid(),
                        'userId' => $item->createdBy,
                        'shortLinkId' => $item->id,
                        'type' => $request->has('type') ? $request->type : null,
                        'userIP' => $request->has('userIP') ? $request->userIP : null,
                        'userLocationCoords' => $request->has('userLocationCoords') ? ZHelpers::zJsonDecode($request->userLocationCoords) : null,
                        'userDeviceInfo' => $request->has('userDeviceInfo') ? ZHelpers::zJsonDecode($request->userDeviceInfo) : null,
                        'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
                    ]);

                    if ($result) {
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new SLPublicPageResource($item)
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'shortLink' => 'Short link not found.'
                    ]);
                }
            } else {
                return ZHelpers::sendBackInvalidParamsResponse([
                    'urlPath' => 'invalid url path.'
                ]);
            }
        } catch (\Throwable $th) {

            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function checkShortLinkPassword(Request $request, $urlPath)
    {
        try {
            //code...
            if ($urlPath && Str::length($urlPath) === 6) {
                $item = ShortLink::where('shortUrlPath', $urlPath)->first();

                if ($item) {
                    $request->validate([
                        'password' => 'required|string|max:250',
                    ]);

                    $linkPasswordEnable =
                        $item->password['enabled'];

                    $linkPassword = $item->password['password'];

                    if ($linkPasswordEnable) {
                        if ($linkPassword === $request->password) {
                            return
                                ZHelpers::sendBackRequestCompletedResponse([
                                    'item' => [
                                        'success' => true
                                    ]
                                ]);
                        } else {
                            return ZHelpers::sendBackBadRequestResponse([
                                'password' => ['Wrong password.']
                            ]);
                        }
                    }
                } else {
                    return ZHelpers::sendBackInvalidParamsResponse([
                        'urlPath' => 'invalid url path.'
                    ]);
                }
            } else {
                return ZHelpers::sendBackInvalidParamsResponse([
                    'urlPath' => 'invalid url path.'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
