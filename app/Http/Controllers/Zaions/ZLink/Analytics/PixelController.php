<?php

namespace App\Http\Controllers\Zaions\ZLink\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\Analytics\PixelResource;
use App\Models\ZLink\Analytics\Pixel;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PixelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentUser = $request->user();

        // check for access
        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $userId = $currentUser->id;
        try {
            $itemsCount = Pixel::where('userId', $userId)->count();
            $items = Pixel::where('userId', $userId)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => PixelResource::collection($items),
                    'itemsCount' => $itemsCount
                ],
                'status' => 200
            ], 200);
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
            'platform' => 'required|string|max:250',
            'title' => 'required|string|max:250',
            'pixelId' => 'required|string|max:250',
            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $userId = $currentUser->id;
        try {
            $result = Pixel::create([
                'uniqueId' => uniqid(),
                'userId' => $userId,
                'platform' => $request->has('platform') ? $request->platform : null,
                'title' => $request->has('title') ? $request->title : null,
                'pixelId' => $request->has('pixelId') ? $request->pixelId : null,
                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive
                    : null,
                'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new PixelResource($result)
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


        try {
            $item = Pixel::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new PixelResource($item)
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
            'platform' => 'required|string|max:250',
            'title' => 'required|string|max:250',
            'pixelId' => 'required|string|max:250',
            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $item = Pixel::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->update([
                    'platform' => $request->has('platform') ? $request->platform : $request->platform,
                    'title' => $request->has('title') ? $request->title : $request->title,
                    'pixelId' => $request->has('pixelId') ? $request->pixelId : $request->pixelId,
                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $request->sortOrderNo,
                    'isActive' => $request->has('isActive') ? $request->isActive
                        : $request->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $request->extraAttributes,
                ]);

                $item = Pixel::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new PixelResource($item)
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_pixel->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $item = Pixel::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

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
