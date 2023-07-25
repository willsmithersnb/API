<?php

namespace App\Mail\Customer\Profile;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class SubscriptionPending extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tenant_template = '.customer.ai.new_subscription';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Subscription Pending")->view($this->getTemplateName());
    }
}
