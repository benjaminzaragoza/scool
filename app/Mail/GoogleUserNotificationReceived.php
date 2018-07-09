<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class GoogleUserNotificationReceived.
 *
 * @package App\Mail
 */
class GoogleUserNotificationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $googleHeaders = collect($this->request->headers)->filter(function ($header) {
            return starts_with($header,'X-Goog') ;
        });

        return $this->markdown('tenants.emails.google.notification')
            ->with([
                'googleHeaders' => $googleHeaders,
                'headers' => $this->request->headers,
                'request' => json_encode($this->request)
            ]);
    }
}
