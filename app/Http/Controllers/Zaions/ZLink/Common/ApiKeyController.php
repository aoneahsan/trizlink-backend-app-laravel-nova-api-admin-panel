<?php

namespace App\Http\Controllers\Zaions\ZLink\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\Common\ApiKeyResource;
use App\Models\ZLink\Common\ApiKey;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApiKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $workspaceId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_apiKey->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $itemsCount = ApiKey::where('userId', $currentUser->id)->count();
            $items = ApiKey::where('userId', $currentUser->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => ApiKeyResource::collection($items),
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
        $request->validate([
            'title' => 'required|string|max:250',
            'clientId' => 'required|string|max:250',
            'clientSecret' => 'required|string|max:250',
            'expireDate' => 'nullable|string',

            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_apiKey->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $result = ApiKey::create([
                'uniqueId' => uniqid(),
                'userId' => $currentUser->id,
                'title' => $request->has('title') ? $request->title : null,
                'clientId' => $request->has('clientId') ? $request->clientId : null,
                'clientSecret' => $request->has('clientSecret') ? $request->clientSecret : null,
                'expireDate' => $request->has('expireDate') ? $request->expireDate : null,

                // 'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive
                    : null,
                'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new ApiKeyResource($result)
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_apiKey->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $item = ApiKey::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new ApiKeyResource($item)
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
        $request->validate([
            'title' => 'required|string|max:250',
            'clientId' => 'required|string|max:250',
            'clientSecret' => 'required|string|max:250',
            'expireDate' => 'nullable|string',

            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_apiKey->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $item = ApiKey::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->update([
                    'title' => $request->has('title') ? $request->title :  $request->title,
                    'clientId' => $request->has('clientId') ? $request->clientId : $request->clientId,
                    'clientSecret' => $request->has('clientSecret') ? $request->clientSecret : $request->clientSecret,
                    'expireDate' => $request->has('expireDate') ? $request->expireDate : $request->expireDate,

                    // 'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $request->sortOrderNo,
                    'isActive' => $request->has('isActive') ? $request->isActive
                        : $request->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $request->extraAttributes,
                ]);

                $item = ApiKey::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new ApiKeyResource($item)
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_apiKey->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $item = ApiKey::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

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
