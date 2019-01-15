<?php

namespace App\Http\Controllers\Tenant\Api\Positions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Positions\PositionSendEmail;
use App\Mail\PositionAssigned;
use App\Models\Position;
use Mail;

/**
 * Class PositionsSendPositionAssignedEmailController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class PositionsSendPositionAssignedEmailController extends Controller
{
    public function send(PositionSendEmail $request, $tenant, Position $position)
    {
        foreach ($position->users as $user) {
            Mail::to($user)->send(new PositionAssigned($position));
        }
    }
}
