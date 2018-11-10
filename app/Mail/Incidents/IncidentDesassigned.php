<?php

namespace App\Mail\Incidents;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class IncidentDesassigned.
 *
 * @package App\Mail\Incidents
 */
class IncidentDesassigned extends Mailable
{
    use Queueable, SerializesModels;

    public $incident;

    /**
     * IncidentClosed constructor.
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
        return ellipsis('Incidència Desassignada (' . $this->incident->id . '): ' . $this->incident->subject, 80);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('tenants.emails.incidents.desassigned')->subject($this->getSubject());
    }
}
