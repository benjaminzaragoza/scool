<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Requests\Incidents\ListIncidentReplies;
use App\Http\Requests\Incidents\StoreIncidentReplies;
use App\Models\Incident;
use App\Models\Reply;
use Auth;

/**
 * Class IncidentRepliesController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class IncidentRepliesController
{
    /**
     * @param ListIncidentReplies $request
     * @param $tenant
     * @param Incident $incident
     * @return mixed
     */
    public function index(ListIncidentReplies $request, $tenant, Incident $incident)
    {
        return $incident->replies;
    }

    /**
     * @param StoreIncidentReplies $request
     * @param $tenant
     * @param Incident $incident
     * @return mixed
     */
    public function store(StoreIncidentReplies $request, $tenant, Incident $incident)
    {
        $reply = Reply::create([
            'body' => $request->body,
            'user_id' => Auth::user()->id
        ]);
        $incident->addReply($reply);
        return $reply->map();
    }
}
