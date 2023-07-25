<?php

namespace App\Mail\Customer\Profile;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Traits\MultiTenantTemplateable;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tenant_template = '.customer.profile.reset_password';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Reset Password")->view($this->getTemplateName());
    }
}
