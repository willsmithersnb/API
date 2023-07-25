<?php

namespace App\Mail\Customer\Profile;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInviteEmail extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    protected $name;
    protected $email;
    protected $get_started_url;
    protected $invited_by;
    protected $company_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $get_started_url, $invited_by, $company_name)
    {
        $this->tenant_template = '.customer.invites.user_invite';
        $this->name = $name;
        $this->email = $email;
        $this->get_started_url = $get_started_url;
        $this->invited_by = $invited_by;
        $this->company_name = $company_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name') . " Invitation")->view($this->getTemplateName())
            ->with([
                'customerName' => $this->name,
                'invitedBy' => $this->invited_by,
                'companyName' => $this->company_name,
                'getStartedUrl' => $this->get_started_url
            ]);
    }
}
