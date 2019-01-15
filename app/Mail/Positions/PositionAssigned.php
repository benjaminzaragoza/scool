<?php

namespace App\Mail\Positions;

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
     * Get subject.
     * @return string
     */
    protected function getSubject()
    {
        return ellipsis('Càrrec assignat (' . $this->position->name . ')', 80);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('tenants.emails.positions.assigned')->subject($this->getSubject());
    }
}
