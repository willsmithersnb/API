<?php

namespace App\Mail\Admin\Messages;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminMessagesReceived extends Mailable
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

        $this->tenant_template = '.admin.messages.admin_message';
        $this->name = $name;
        $this->messageBody = $messageBody;
        $this->messageUrl = config('app.lux_url') . '/profile/my-questions';
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
