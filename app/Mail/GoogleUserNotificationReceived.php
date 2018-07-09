<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        $googleHeaders = collect($this->request->headers)->filter(function ($header, $headerKey) {
            return starts_with($headerKey,'x-goog-');
        });
        $googleHeaders = $googleHeaders->map(function ($header) {
            return $header[0];
        });
        $headers = collect($this->request->headers)->map(function ($header) {
            return $header[0];
        });
        $type = '';
        if (array_key_exists('x-goog-resource-state',$googleHeaders)) {
            $type = $googleHeaders['x-goog-resource-state'];
        };
        $body = $this->request->getContent();
        return $this->markdown('tenants.emails.google.notification')
            ->with([
                'type' => $type,
                'body' => $body,
                'googleHeaders' => collect($googleHeaders),
                'headers' => $headers,
                'request' => json_encode($this->request)
            ]);
    }
}
