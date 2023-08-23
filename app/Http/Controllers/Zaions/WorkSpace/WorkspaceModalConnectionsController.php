<?php

namespace App\Http\Controllers\Zaions\WorkSpace;

use App\Http\Controllers\Controller;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\Analytics\Pixel;
use App\Models\ZLink\Analytics\UtmTag;
use App\Zaions\Enums\ModalsEnum;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WorkspaceModalConnectionsController extends Controller
{
    //
    public function viewAll(Request $request, $workspaceId, $modalType)
    {

        if (!$modalType) {
            return ZHelpers::sendBackRequestFailedResponse([
                'item' => ['Modal type is incorrect!']
            ]);
        }
        $currentUser = $request->user();

        if ($modalType === ModalsEnum::pixel->name) {
            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_workspace_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
        } elseif ($modalType === ModalsEnum::UTMTag->name) {
            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_workspace_utm_tag->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
        }

        try {
            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();
            switch ($modalType) {
                case ModalsEnum::pixel->name:
                    $pixels = $workspace->pixel;

                    return response()->json([
                        'success' => true,
                        'errors' => [],
                        'message' => 'Request pixels Completed Successfully!',
                        'data' => [
                            'pixels' => $pixels,
                        ],
                        'status' => 200
                    ]);

                case ModalsEnum::UTMTag->name:
                    $UTMTag = $workspace->UTMTag();
                    return response()->json([
                        'success' => true,
                        'errors' => [],
                        'message' => 'Request utm tag Completed Successfully!',
                        'data' => [
                            'items' => $UTMTag
                        ],
                        'status' => 200
                    ]);

                default:
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Modal Not found!']
                    ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function detach(Request $request, $workspaceId, $modalType, $modalId)
    {
        if (!$modalId || !$modalType) {
            return ZHelpers::sendBackRequestFailedResponse([
                'item' => ['Modal information is incorrect!']
            ]);
        }

        $currentUser = $request->user();

        if ($modalType === ModalsEnum::pixel->name) {
            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::attach_pixel_to_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
        } elseif ($modalType === ModalsEnum::UTMTag->name) {
            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::attach_utm_tag_to_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
        }

        try {
            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();
            switch ($modalType) {
                case ModalsEnum::pixel->name:
                    $pixel = Pixel::where('id', $modalId)->first();

                    if ($pixel) {
                        $pixels = $workspace->pixel()->detach($modalId, ['userId' => $currentUser->id]);

                        return response()->json([
                            'success' => true,
                            'errors' => [],
                            'message' => 'Request detach pixels Completed Successfully!',
                            'data' => [
                                'pixels' => $pixels,
                            ],
                            'status' => 200
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([
                            'item' => ['pixel Not found!']
                        ]);
                    }

                case ModalsEnum::UTMTag->name:
                    $UTMTag = UtmTag::where('id', $modalId)->first();

                    if ($UTMTag) {
                        $UTMTag = $workspace->UTMTag()->detach($modalId, ['userId' => $currentUser->id]);

                        return response()->json([
                            'success' => true,
                            'errors' => [],
                            'message' => 'Request detach utm tag Completed Successfully!',
                            'data' => [
                                'UTMTag' => $UTMTag,
                            ],
                            'status' => 200
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([
                            'item' => ['UTM Tag Not found!']
                        ]);
                    }

                default:
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Modal Not found!']
                    ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }


    public function attach(Request $request, $workspaceId, $modalType, $modalId)
    {
        if (!$modalId || !$modalType) {
            return ZHelpers::sendBackRequestFailedResponse([
                'item' => ['Modal information is incorrect!']
            ]);
        }

        $currentUser = $request->user();

        if ($modalType === ModalsEnum::pixel->name) {
            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::detach_pixel_from_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
        } elseif ($modalType === ModalsEnum::UTMTag->name) {
            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::detach_utm_tag_from_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
        }

        try {
            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();
            switch ($modalType) {
                case ModalsEnum::pixel->name:
                    $pixel = Pixel::where('id', $modalId)->first();

                    if ($pixel) {
                        $pixels = $workspace->pixel()->attach($modalId, ['userId' => $currentUser->id]);

                        return response()->json([
                            'success' => true,
                            'errors' => [],
                            'message' => 'Request attach pixels Completed Successfully!',
                            'data' => [
                                'pixels' => $pixels,
                            ],
                            'status' => 200
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([
                            'item' => ['pixel Not found!']
                        ]);
                    }

                case ModalsEnum::UTMTag->name:
                    $UTMTag = UtmTag::where('id', $modalId)->first();

                    if ($UTMTag) {
                        $UTMTag = $workspace->UTMTag()->attach($modalId, ['userId' => $currentUser->id]);

                        return response()->json([
                            'success' => true,
                            'errors' => [],
                            'message' => 'Request attach utm tag Completed Successfully!',
                            'data' => [
                                'UTMTag' => $UTMTag,
                            ],
                            'status' => 200
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([
                            'item' => ['UTM Tag Not found!']
                        ]);
                    }

                default:
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Modal Not found!']
                    ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
