<?php

namespace App\Mail\Incidents;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class IncidentDeleted
 * @package App\Mail
 */
class IncidentDeleted extends Mailable
{
    use Queueable;

    public $incident;

    /**
     * IncidentDeleted constructor.
     *
     * @param $incident
     */
    public function __construct(array $incident)
    {
        $this->incident = Incident::make($incident);
        $this->incident->user_id = ((int) $incident['user_id']);
        $this->incident->created_at = ( $incident['created_at']);
        $this->incident->updated_at = ( $incident['updated_at']);
    }

    /**
     * Get subject.
     * @return string
     */
    protected function getSubject()
    {
        return ellipsis('Incidència esborrada (' . $this->incident->id . '): ' . $this->incident->subject, 80);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('tenants.emails.incidents.deleted')->subject($this->getSubject());
    }
}
