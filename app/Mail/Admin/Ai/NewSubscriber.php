<?php

namespace App\Mail\Admin\Ai;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSubscriber extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    protected $name;
    protected $email;
    protected $couponCode;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fullName, $email, $couponCode)
    {
        $this->tenant_template = '.admin.ai.new_subscription';
        $this->name = $fullName;
        $this->email = $email;
        $this->couponCode = $couponCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("New Subscriber")->view($this->getTemplateName())
            ->with([
                'fullName' => $this->name,
                'email' => $this->email,
                'has_coupon_code' => $this->couponCode
            ]);
    }
}
