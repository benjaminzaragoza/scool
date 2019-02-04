<?php

namespace App\Http\Controllers\Tenant\Api\Person\IdentifierTypes;

use App\Http\Controllers\Tenant\Controller;
use App\Models\IdentifierType;
use Illuminate\Http\Request;

/**
 * Class IdentifierTypesController
 *
 * @package App\Http\Controllers
 */
class IdentifierTypesController extends Controller
{

    /**
     * Index.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return map_collection(IdentifierType::all());
    }

}
