<?php

namespace App\Http\Controllers\Tenant\Api;

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
        $reply->body = $request->body;
        $reply->save();
        return $reply->map();
    }
}
