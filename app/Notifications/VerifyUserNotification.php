<?php

namespace App\Notifications;

use App\Mail\Customer\Profile\VerifyUserMail;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyUserNotification extends VerifyEmail
{
    public $token;

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verification_url = $this->verificationUrl($notifiable);
        return (new VerifyUserMail($verification_url))->to($notifiable->email);
    }
}
