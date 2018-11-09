<?php

namespace App\Mail;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class IncidentOpened.
 *
 * @package App\Mail
 */
class IncidentOpened extends Mailable
{
    use Queueable, SerializesModels;

    public $incident;

    /**
     * IncidentOpened constructor.
     * @param $incident
     */
    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    }

    /**
     * Get subject.
     * @return string
     */
    protected function getSubject()
    {
        return ellipsis('Incidència reoberta (' . $this->incident->id . '): ' . $this->incident->subject, 80);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('tenants.emails.incidents.opened')->subject($this->getSubject());
    }
}
