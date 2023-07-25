<?php

namespace App\Traits;

use Illuminate\Mail\Mailable;

trait MultiTenantTemplateable
{
    protected String $tenant;
    protected String $tenant_template = '';
    protected String $prefix;
    protected String $suffix;

    public function getTemplatePrefix()
    {
        return isset($this->prefix) ? $this->prefix : (($this instanceof Mailable) ? 'emails.' : '');
    }

    public function getTemplateTenant()
    {
        return isset($this->tenant) ? $this->tenant : config('app.tenant');
    }

    public function getTemplateSuffix()
    {
        return isset($this->tenant) ? $this->tenant : '';
    }

    public function getTemplateName()
    {
        return $this->getTemplatePrefix() . $this->getTemplateTenant() . $this->tenant_template . $this->getTemplateSuffix();
    }
}
