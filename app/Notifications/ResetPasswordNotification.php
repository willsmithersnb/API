<?php

namespace App\Notifications;

use App\Mail\Customer\Profile\ResetPasswordEmail;
use Illuminate\Auth\Notifications\ResetPassword;

class ResetPasswordNotification extends ResetPassword
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
        return (new ResetPasswordEmail())->with('token', $this->token)->to($notifiable->email);
    }
}
