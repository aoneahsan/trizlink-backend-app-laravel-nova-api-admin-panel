<?php

namespace App\Http\Controllers\Zaions\ZLink\LinkInBios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\LinkInBios\LibPredefinedDataResource;
use App\Models\ZLink\LinkInBios\LibPredefinedData;
use App\Models\ZLink\LinkInBios\LinkInBio;
use App\Zaions\Enums\LibPreDefinedDataModalEnum;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class LibPredefinedDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $pddType)
    {
        // $validator = Validator::make($pddType, [
        //     LibPreDefinedDataModalEnum::block,
        //     LibPreDefinedDataModalEnum::musicPlatform,
        //     LibPreDefinedDataModalEnum::messengerPlatform,
        //     LibPreDefinedDataModalEnum::socialPlatform,
        //     LibPreDefinedDataModalEnum::formField,
        //     LibPreDefinedDataModalEnum::blocks,
        // ]);

        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_libPerDefinedData->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


            if (!$pddType) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No modal found!'],
                ]);
            }

            // getting workspace
            // $Pdd = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            // getting link-in-bio in workspace
            // $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

            // if (!$linkInBio) {
            //     return ZHelpers::sendBackInvalidParamsResponse([
            //         "item" => ['No link-in-bio found!']
            //     ]);
            // }

            $itemsCount = LibPredefinedData::where('userId', $currentUser->id)->where('preDefinedDataType', $pddType)->count();
            $items = LibPredefinedData::where('userId', $currentUser->id)->where('preDefinedDataType', $pddType)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => LibPredefinedDataResource::collection($items),
                    'itemsCount' => $itemsCount
                ],
                'status' => 200,
                'author' => 'MTI'
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
    public function store(Request $request, $pddType)
    {

        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_libPerDefinedData->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


        if (!$pddType) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No modal found!'],
            ]);
        }

        $request->validate([
            'type' => 'required|string',
            'icon' => 'required|string',
            'title' => 'required|string',
            'preDefinedDataType' => 'nullable|string',
            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'background' => 'nullable|json',
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $result = LibPredefinedData::create([
                'uniqueId' => uniqid(),
                'userId' => $currentUser->id,
                'type' => $request->has('type') ? $request->type : null,
                'icon' => $request->has('icon') ? $request->icon : null,
                'title' => $request->has('title') ? $request->title : null,
                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'background' => $request->has('background') ? $request->background : null,
                'preDefinedDataType' => $request->has('preDefinedDataType') ? $request->preDefinedDataType : null,
                'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LibPredefinedDataResource($result)
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
    public function show(Request $request, $pddType, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_libPerDefinedData->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            // getting workspace
            // $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            // // getting link-in-bio in workspace
            // $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

            // if (!$linkInBio) {
            //     return ZHelpers::sendBackInvalidParamsResponse([
            //         "item" => ['No link-in-bio found!']
            //     ]);
            // }

            $item = LibPredefinedData::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LibPredefinedDataResource($item)
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
    public function update(Request $request, $pddType,  $itemId)
    {

        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_libPerDefinedData->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        // // getting workspace
        // $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        // // getting link-in-bio in workspace
        // $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

        // if (!$linkInBio) {
        //     return ZHelpers::sendBackInvalidParamsResponse([
        //         "item" => ['No link-in-bio found!']
        //     ]);
        // }

        $request->validate([
            'type' => 'required|string',
            'icon' => 'required|string',
            'title' => 'required|string',
            'preDefinedDataType' => 'nullable|string',
            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'background' => 'nullable|json',
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $item = LibPredefinedData::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->update([
                    'type' => $request->has('type') ? $request->type : $item->type,
                    'icon' => $request->has('icon') ? $request->icon : $item->icon,
                    'title' => $request->has('title') ? $request->title : $item->title,
                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                    'isActive' => $request->has('isActive') ? $request->isActive : null,
                    'background' => $request->has('background') ? $request->background : null,
                    'preDefinedDataType' => $request->has('preDefinedDataType') ? $request->preDefinedDataType : $request->preDefinedDataType,
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $request->extraAttributes,
                ]);

                $item = LibPredefinedData::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LibPredefinedDataResource($item)
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
    public function destroy(Request $request, $pddType,  $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_libPerDefinedData->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        // getting workspace
        // $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        // // getting link-in-bio in workspace
        // $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

        // if (!$linkInBio) {
        //     return ZHelpers::sendBackInvalidParamsResponse([
        //         "item" => ['No link-in-bio found!']
        //     ]);
        // }

        try {
            $item = LibPredefinedData::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

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
