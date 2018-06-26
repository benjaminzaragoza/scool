<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowPersonalData;
use App\Models\Person;

/**
 * Class PersonalDataController.
 *
 * @package App\Http\Controllers\Tenant
 */
class PersonalDataController extends Controller
{
    /**
     * Show.
     *
     * @param ShowPersonalData $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowPersonalData $request)
    {
        $people = Person::all();
        return view ('tenants.people.show', compact('people'));
    }


}
