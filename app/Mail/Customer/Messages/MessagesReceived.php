<?php

namespace App\Mail\Customer\Messages;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessagesReceived extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    protected $messageSubject;
    protected $name;
    protected $messageBody;
    protected $messageUrl;
    protected $messageThreadSubject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $messageBody, $messageThreadSubject, $messageThreadId)
    {
        $this->tenant_template = '.customer.messages.customer_message';
        $this->name = $name;
        $this->messageBody = $messageBody;
        $this->messageUrl = config('app.admin_url') . "/customers/customerMessages/" . $messageThreadId;
        $this->messageThreadSubject = $messageThreadSubject;
        $this->messageSubject = $messageThreadSubject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("New Message : " . $this->messageSubject)->view($this->getTemplateName())
            ->with([
                'messageSubject' => $this->messageSubject,
                'messageBody' => $this->messageBody,
                'customerName' => $this->name,
                'messageUrl' => $this->messageUrl
            ]);
    }
}
