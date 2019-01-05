<?php

namespace App\Http\Controllers\Tenant\Api\Positions;

use App\Events\Positions\PositionDeleted;
use App\Events\Positions\PositionStored;
use App\Http\Requests\Positions\PositionIndex;
use App\Http\Requests\Positions\PositionShow;
use App\Http\Requests\Positions\PositionStore;
use App\Http\Requests\Positions\PositionDestroy;
use App\Http\Controllers\Controller;
use App\Models\Position;

/**
 * Class PositionsController.
 *
 * @package App\Http\Controllers\Api
 */
class PositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param PositionIndex $request
     * @return mixed
     */
    public function index(PositionIndex $request)
    {
        return map_collection(Position::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PositionStore $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionStore $request)
    {
        $position = Position::create([
            'name' => $request->name,
            'shortname' => $request->shortname,
            'code' => $request->code
        ]);
        event(new PositionStored($position));
        return $position->map();
    }

    /**
     * Display the specified resource.
     *
     * @param PositionShow $request
     * @param $tenant
     * @param Position $position
     * @return array
     */
    public function show(PositionShow $request, $tenant,Position $position)
    {
//        event(new PositionShowed($incident));
//        return $incident->map();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PositionDestroy $request
     * @param $tenant
     * @param Position $position
     * @return Position
     * @throws \Exception
     */
    public function destroy(PositionDestroy $request, $tenant, Position $position)
    {
        $oldPosition = $position->map();
        $position->delete();
        event(new PositionDeleted($oldPosition));
        return $position;
    }
}
