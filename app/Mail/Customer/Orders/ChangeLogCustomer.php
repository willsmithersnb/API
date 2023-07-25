<?php

namespace App\Mail\Customer\Orders;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangeLogCustomer extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    protected $orderName;
    protected $orderId;
    protected $statusName;
    protected $previousStatus;
    protected $changeType;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderId, $changeType, $orderName, $statusName, $previousStatus)
    {
        $this->tenant_template = '.customer.orders.changelog_customer';
        $this->orderId = $orderId;
        $this->changeType = $changeType;
        $this->orderName = $orderName;
        $this->statusName = $statusName;
        $this->previousStatus = $previousStatus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Order Notification | # " . $this->orderId)->view($this->getTemplateName())
            ->with([
                'orderId' => $this->orderId,
                'changeType' => $this->changeType,
                'orderName' => $this->orderName,
                'statusName' => $this->statusName,
                'previousStatus' => $this->previousStatus
            ]);
    }
}
