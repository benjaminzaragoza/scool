<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Events\Incidents\IncidentReplyUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Replies\UpdateBodyReply;
use App\Models\Reply;

/**
 * Class RepliesBodyController
 */
class RepliesBodyController extends Controller {

    /**
     * @param UpdateBodyReply $request
     * @param $tenant
     * @param Reply $reply
     * @return array
     */
    public function update(UpdateBodyReply $request, $tenant, Reply $reply)
    {
        $oldReply = clone($reply);
        $reply->body = $request->body;
        $reply->save();
        event(new IncidentReplyUpdated($reply->incident,$reply, $oldReply));
        return $reply->map();
    }
}
