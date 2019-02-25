<?php

namespace App\Mail\Users\Password;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class PasswordChanged.
 *
 * @package App\Mail\Users\Password
 */
class PasswordChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    /**
     * PasswordChanged constructor.
     * @param $user
     * @param $password
     */
    public function __construct(User $user, String $password)
    {
        $this->user = $user;
        $this->password = $password;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('tenants.emails.users.passwordchanged');
    }
}
