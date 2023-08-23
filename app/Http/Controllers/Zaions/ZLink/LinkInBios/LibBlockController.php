<?php

namespace App\Http\Controllers\Zaions\ZLink\LinkInBios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\LinkInBios\LibBlockResource;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\LinkInBios\LibBlock;
use App\Models\ZLink\LinkInBios\LinkInBio;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LibBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $workspaceId, $linkInBioId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_libBlock->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            // getting workspace
            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            // getting link-in-bio in workspace
            $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

            if (!$linkInBio) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No link-in-bio found!']
                ]);
            }

            $itemsCount = LibBlock::where('linkInBioId', $linkInBio->id)->where('userId', $currentUser->id)->count();
            $items = LibBlock::where('linkInBioId', $linkInBio->id)->where('userId', $currentUser->id)->orderBy('sortOrderNo', 'asc')->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => LibBlockResource::collection($items),
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
    public function store(Request $request, $workspaceId, $linkInBioId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_libBlock->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        // getting link-in-bio in workspace
        $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

        if (!$linkInBio) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No link-in-bio found!']
            ]);
        }

        $request->validate([
            'blockType' => 'nullable|string',
            'blockContent' => 'nullable|json',
            // 'isActive' => 'nullable|boolean', // will be true for new one
            // 'sortOrderNo' => 'nullable|integer', // we will create the orderNo using the position prop, mainly we will use the "updateSortOrderNo" API to update the orderNo, added here just in case if we change the flow in future
            'position' => 'string', // 'top' | 'bottom'

            'extraAttributes' => 'nullable|json',
        ]);
        $userId = $request->user()->id;

        // return response()->json(['$linkInBioId' => $linkInBioId, "request" => $request->blockType]);

        // we will create this block order number using it's position value.
        // if it's position is top, then it's orderNo should be 0, and we need to shift orderNo of all other blocks of this linkInBio by 1.
        // if it's bottom, then all we have to do is find the block with highest orderNo in this linkInBio and add 1 in that orderNo and assign that to this block

        $orderNo = 0;
        if ($request->position === 'top') {
            // $orderNo = 0;
            $linkInBioBlocks = LibBlock::where('linkInBioId', $linkInBioId)->orderBy('sortOrderNo', 'asc')->get();
            // Loop through the items and update the orderNo field
            foreach ($linkInBioBlocks as $index => $block) {
                $item = LibBlock::where('uniqueId', $block->uniqueId)->first();
                if ($item) {
                    $item->sortOrderNo = $index + 1; // Add 1 to start at 1 instead of 0
                    $item->save();
                }
            }
        } else if ($request->position === 'bottom') {
            $linkInBioBlockWithHighestOrderNo = LibBlock::where('linkInBioId', $linkInBioId)->orderBy('sortOrderNo', 'desc')->first();
            $orderNo = $linkInBioBlockWithHighestOrderNo ? $linkInBioBlockWithHighestOrderNo->sortOrderNo + 1 : 1;
        } else {
            return ZHelpers::sendBackRequestFailedResponse([
                'position' => 'Invalid position parameter passed, position can be either "top" or "bottom", please try again.'
            ]);
        }

        try {
            $parentItem = LinkInBio::where('uniqueId', $linkInBioId)->first();
            if ($parentItem) {
                $result = LibBlock::create([
                    'uniqueId' => uniqid(),
                    'userId' => $userId,
                    'linkInBioId' => $linkInBio->id,
                    'blockType' => $request->has('blockType') ? $request->blockType : null,
                    'blockContent' => $request->has('blockContent') ? ZHelpers::zJsonDecode($request->blockContent) : null,
                    'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
                    'isActive' =>
                    $request->has('isActive') ? $request->isActive : true,
                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $orderNo,
                ]);

                if ($result) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new LibBlockResource($result)
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'linkInBio' => 'Link In Bio not found with given id.'
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
    public function show(Request $request, $workspaceId, $linkInBioId, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_libBlock->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        // getting link-in-bio in workspace
        $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

        if (!$linkInBio) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No link-in-bio found!']
            ]);
        }
        try {
            $item = LibBlock::where('uniqueId', $itemId)->where('linkInBioId', $linkInBio->id)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LibBlockResource($item)
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
    public function update(Request $request, $workspaceId, $linkInBioId, $itemId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_libBlock->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

        // getting link-in-bio in workspace
        $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

        if (!$linkInBio) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No link-in-bio found!']
            ]);
        }
        $request->validate([
            'blockType' => 'nullable|string',
            'blockContent' => 'nullable|json',
            'isActive' => 'nullable|boolean',
            'sortOrderNo' => 'nullable|integer',  // mainly we will use the "updateSortOrderNo" API to update the orderNo
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $item = LibBlock::where('uniqueId', $itemId)->where('linkInBioId', $linkInBio->id)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->update([
                    'blockType' => $request->has('blockType') ? $request->blockType : $item->blockType,
                    'blockContent' => $request->has('blockContent') ? ZHelpers::zJsonDecode($request->blockContent) : ZHelpers::zJsonDecode($item->blockContent),
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $item->extraAttributes,
                    'isActive' =>
                    $request->has('isActive') ? $request->isActive : $request->isActive,
                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->sortOrderNo,

                ]);

                $item = LibBlock::where('uniqueId', $itemId)->where('linkInBioId', $linkInBio->id)->where('userId', $currentUser->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LibBlockResource($item)
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
    public function destroy(Request $request, $workspaceId, $linkInBioId, $itemId)
    {


        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_libBlock->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            // getting workspace
            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            // getting link-in-bio in workspace
            $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->first();

            if (!$linkInBio) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No link-in-bio found!']
                ]);
            }

            $item = LibBlock::where('uniqueId', $itemId)->where('linkInBioId', $linkInBio->id)->where('userId', $currentUser->id)->first();

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

    public function updateSortOrderNo(Request $request)
    {
        $request->validate([
            'items' => 'required' // array of all items ids ()  {id: string}[] // items will be respective to current model
        ]);

        try {
            $items = $request->input('items');

            // Loop through the items and update the orderNo field
            foreach ($items as $order => $item) {
                $item = LibBlock::where('unique_id', $item)->first();
                if ($item) {
                    $item->orderNo = $order + 1; // Add 1 to start at 1 instead of 0
                    $item->save();
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
