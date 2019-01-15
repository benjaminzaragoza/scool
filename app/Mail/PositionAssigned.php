<?php

namespace App\Mail;

use App\Models\Position;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PositionAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $position;

    /**
     * PositionAssigned constructor.
     * @param $position
     */
    public function __construct(Position $position)
    {
        $this->position = $position;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('tenants.emails.positions.assigned');
    }
}
