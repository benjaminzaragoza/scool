<?php

namespace App\Http\Controllers\Tenant\Api\Person\Identifiers;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\People\CheckIdentifierStore;
use App\Models\Identifier;
use App\Models\IdentifierType;
use Illuminate\Http\Request;

/**
 * Class CheckIdentifierController
 *
 * @package App\Http\Controllers
 */
class CheckIdentifierController extends Controller
{

    /**
     * Store.
     * @param Request $request
     * @return mixed
     */
    public function store(CheckIdentifierStore $request)
    {
        $identifier = Identifier::where('value',$request->identifier_value)
            ->where('type_id',$request->identifier_type_id)->first();
        if ($identifier) return json_encode(true);
        return json_encode(false);
    }

}
