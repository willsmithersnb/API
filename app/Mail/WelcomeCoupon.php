<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Traits\MultiTenantTemplateable;

class WelcomeCoupon extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tenant_template = '.customer.coupons.coupon';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Welcome Coupon")->view($this->getTemplateName());
    }
}
