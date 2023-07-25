<?php

namespace App\Mail\Customer\Profile;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyUserMail extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    protected $verification_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verification_url)
    {
        $this->tenant_template = '.customer.profile.user_verification';
        $this->verification_url = $verification_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Email Verification")->view($this->getTemplateName())
            ->with([
                'verificationUrl' => $this->verification_url
            ]);
    }
}
