<?php

namespace App\Mail\Admin\Invites;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminInvite extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    protected $signInUrl;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->signInUrl = config('app.admin_url');
        $this->tenant_template = '.admin.invites.admin_invite';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Admin Access Granted")->view($this->getTemplateName())
            ->with([
                'signInUrl' => $this->signInUrl
            ]);
    }
}
