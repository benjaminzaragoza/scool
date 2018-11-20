<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Events\Incidents\IncidentReplyAdded;
use App\Events\Incidents\IncidentReplyRemoved;
use App\Events\Incidents\IncidentReplyUpdated;
use App\Http\Requests\Incidents\DestroyIncidentReplies;
use App\Http\Requests\Incidents\ListIncidentReplies;
use App\Http\Requests\Incidents\StoreIncidentReplies;
use App\Http\Requests\Incidents\UpdateIncidentReplies;
use App\Mail\Incidents\IncidentCommentAdded;
use App\Models\Incident;
use App\Models\Reply;
use App\Models\Setting;
use Auth;
use Mail;

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
     * Store.
     *
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

        event(new IncidentReplyAdded($incident,$reply));

        return $reply->map();
    }

    /**
     * @param UpdateIncidentReplies $request
     * @param $tenant
     * @param Incident $incident
     * @param Reply $reply
     * @return Reply
     * @throws \Exception
     */
    public function update(UpdateIncidentReplies $request, $tenant, Incident $incident, Reply $reply)
    {
        $oldReply = clone($reply);
        $reply->body = $request->body;
        $reply->save();
        event(new IncidentReplyUpdated($incident,$reply, $oldReply));
        return $reply;
    }

    /**
     * @param DestroyIncidentReplies $request
     * @param $tenant
     * @param Incident $incident
     * @param Reply $reply
     * @return Reply
     * @throws \Exception
     */
    public function destroy(DestroyIncidentReplies $request, $tenant, Incident $incident, Reply $reply)
    {
        $oldReply = clone($reply);
        $reply->delete();
        event(new IncidentReplyRemoved($incident,$oldReply));
        return $reply;
    }
}
