<?php

namespace App\Http\Controllers\Zaions\Testing;

use App\Http\Controllers\Controller;
use App\Models\Default\User;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class TestController extends Controller
{
    public function notifyUser(Request $request)
    {
        $user = User::where('email', env('ADMIN_EMAIL'))->first();

        if ($user) {
            $user->notify(
                NovaNotification::make()
                    ->message('Your report is ready to download.')
                    ->action('Download', URL::remote('https://example.com/report.pdf'))
                    ->icon('download')
                    ->type('info')
            );
            return ZHelpers::sendBackRequestCompletedResponse(['message' => 'Notified']);
        } else {
            return ZHelpers::sendBackRequestFailedResponse([
                'item' => 'Not found!'
            ]);
        }
    }

    function testingPaginationInPhp(Request $request)
    {
        // for pagination we need to values, offset and limit variable
        // offset to know how many items to skip and limit to know how many items to return
        $request->validate([
            // search query
            'searchQuery' => 'nullable|string',

            // Filter
            'filterField' => 'nullable|string|required_with:filterValue,filterCondition',
            'filterValue' => 'nullable|string|required_with:filterField,filterCondition',
            'filterCondition' => 'nullable|string|required_with:filterField,filterValue|in:equal,notEqual,like,in,notIn',

            // Pagination
            'paginationOffset' => 'nullable|numeric|min:0',
            'paginationLimit' => 'nullable|numeric|min:1|max:30',
            // 'paginationPageNumber' => 'nullable|numeric', // should be like array index (for first page pass 0, first second pass 1 and so on)

            // Sort | Order
            'sortField' => 'nullable|string|required_with:sortDirection',
            'sortDirection' => ['nullable', 'string', 'in:desc,asc', 'required_with:sortField']
        ]);

        $searchQuery = $request->searchQuery;
        $filterField = $request->filterField;
        $filterValue = $request->filterValue;
        $filterCondition = $request->filterCondition;
        $sortField = $request->sortField;
        $sortDirection = $request->sortDirection;
        $paginationOffset = $request->paginationOffset;
        $paginationLimit = $request->paginationLimit;

        // First we need to do search query, then we should continue with filters, then we should apply the sorting and at the end pagination logic should be applied.
        $usersQuery = User::query();

        // Search Query
        if ($searchQuery) {
            $usersQuery = $usersQuery->where('email', 'LIKE', '%' . $searchQuery . '%');
        }

        // Filter
        if ($filterField && $filterValue && $filterCondition) {
            if ($filterCondition === 'equal') {
                $usersQuery = $usersQuery->where($filterField, '=', $filterValue);
            }
            if ($filterCondition === 'notEqual') {
                $usersQuery = $usersQuery->where($filterField, '!=', $filterValue);
            }
            if ($filterCondition === 'like') {
                $usersQuery = $usersQuery->where($filterField, 'like', '%' . $filterValue . '%');
            }
            // one thing to keep in mind for this in and notIn filter is that you need to pass a "," join array here
            // [1,2,3]
            if ($filterCondition === 'in') {
                $usersQuery = $usersQuery->whereIn($filterField, $filterValue);
            }
            if ($filterCondition === 'notIn') {
                $usersQuery = $usersQuery->whereNotIn($filterField, $filterValue);
            }
        }

        $usersCountQuery = $usersQuery;

        // Sort
        if ($sortField && $sortDirection && ($sortDirection === 'asc' || $sortDirection === 'desc')) {
            $usersQuery = $usersQuery->orderBy($sortField, $sortDirection);
        }

        // Pagination
        // we will show the first 10 items by default
        if ($paginationOffset > -1) {
            $usersQuery = $usersQuery->skip($paginationOffset);
        } else {
            $usersQuery = $usersQuery->skip(0);
        }


        if ($paginationLimit > 0) {
            $usersQuery = $usersQuery->limit($paginationLimit);
        } else {
            // need to make this a config setting to improve the quality (much better a admin panel setting)
            $usersQuery = $usersQuery->limit(10);
        }

        // Actual Result Items
        $usersQueryResult = $usersQuery->get();
        
        // total count items
        $usersCountQueryResult = $usersCountQuery->count();


        // total, currentPage

        return response()->json(['total' => $usersCountQueryResult, 'data' => $usersQueryResult], 200);
    }
}
