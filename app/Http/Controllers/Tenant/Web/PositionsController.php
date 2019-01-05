<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Positions\PositionsIndex;
use App\Models\Position;
use App\Models\User;

/**
 * Class PositionsController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class PositionsController extends Controller
{
    /**
     * Index.
     *
     * @param PositionsIndex $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(PositionsIndex $request)
    {
        $positions = map_collection(Position::with('users')->get());
        $users = map_simple_collection(User::all());
        return view('tenants.positions.index', compact('positions','users'));
    }
}
