<?php

namespace App\Mail\Customer\Profile;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionVerified extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    protected $nbAir;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tenant_template = '.customer.profile.subscription_verified';
        $this->nbAir = config('app.lux_url') . '/nbair';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Subscription Verified")->view($this->getTemplateName())
            ->with(['nbAir' => $this->nbAir]);
    }
}
