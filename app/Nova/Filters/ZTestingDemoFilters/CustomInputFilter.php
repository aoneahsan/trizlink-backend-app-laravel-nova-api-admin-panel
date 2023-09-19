<?php

namespace App\Nova\Filters\ZTestingDemoFilters;

use Illuminate\Http\Request;
use Outl1ne\NovaInputFilter\InputFilter;

class CustomInputFilter extends InputFilter
{
    public function apply(Request $request, $query, $search)
    {
        return $query->where('email', 'like', "%$search%");;
    }
}
