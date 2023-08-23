<?php

namespace App\Http\Controllers\Zaions\ZLink\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\Analytics\UtmTagResource;
use App\Models\ZLink\Analytics\UtmTag;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UtmTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_utmTag->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
        try {
            $itemsCount = UtmTag::where('userId', $currentUser->id)->count();
            $items = UtmTag::where('userId', $currentUser->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => UtmTagResource::collection($items),
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
    public function store(Request $request)
    {
        $request->validate([
            'templateName' => 'required|string|max:250',
            'utmCampaign' => 'required|string|max:250',
            'utmMedium' => 'required|string|max:250',
            'utmSource' => 'required|string|max:250',
            'utmTerm' => 'nullable|string|max:250',
            'utmContent' => 'nullable|string|max:250',

            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_utmTag->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $result = UtmTag::create([
                'uniqueId' => uniqid(),
                'userId' => $currentUser->id,

                'templateName' => $request->has('templateName') ? $request->templateName : null,
                'utmCampaign' => $request->has('utmCampaign') ? $request->utmCampaign : null,
                'utmMedium' => $request->has('utmMedium') ? $request->utmMedium : null,
                'utmSource' => $request->has('utmSource') ? $request->utmSource : null,
                'utmTerm' => $request->has('utmTerm') ? $request->utmTerm : null,
                'utmContent' =>
                $request->has('utmContent') ? $request->utmContent : null,
                'sortOrderNo' =>
                $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' =>
                $request->has('isActive') ? $request->isActive : null,
                'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UtmTagResource($result)
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
    public function show(Request $request, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_utmTag->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
        try {
            $item = UtmTag::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UtmTagResource($item)
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
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'templateName' => 'required|string|max:250',
            'utmCampaign' => 'required|string|max:250',
            'utmMedium' => 'required|string|max:250',
            'utmSource' => 'required|string|max:250',
            'utmTerm' => 'nullable|string|max:250',
            'utmContent' => 'nullable|string|max:250',

            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_utmTag->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
        try {
            $item = UtmTag::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->update([
                    'templateName' => $request->has('templateName') ? $request->templateName : $item->templateName,
                    'utmCampaign' => $request->has('utmCampaign') ? $request->utmCampaign : $item->utmCampaign,
                    'utmMedium' => $request->has('utmMedium') ? $request->utmMedium : $item->utmMedium,
                    'utmSource' => $request->has('utmSource') ? $request->utmSource : $item->utmSource,
                    'utmTerm' => $request->has('utmTerm') ? $request->utmTerm : $item->utmTerm,
                    'utmContent' => $request->has('utmContent') ? $request->utmContent : $item->utmContent,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->sortOrderNo,
                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $item->extraAttributes,
                ]);

                $item = UtmTag::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UtmTagResource($item)
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
    public function destroy(Request $request, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_utmTag->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
        try {
            $item = UtmTag::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

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
