<?php

namespace App\Mail\Customer\Orders;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerOrderTransfer extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;

    protected $orderName;
    protected $referenceNumber;
    protected $companyName;
    protected $total;
    protected $formulationName;
    protected $format;
    protected $formulationWeight;
    protected $predictedOsmolality;
    protected $cgmp;
    protected $totalLiters;
    protected $leadTime;
    protected $orderLink;
    protected $isSpecialized;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($referenceNumber, $orderName, $companyName, $total, $formulationName, $format, $formulationWeight, $predictedOsmolality, $cgmp, $totalLiters, $leadTime, $isSpecialized)
    {
        $this->tenant_template = '.customer.orders.order_transfer_customer';
        $this->referenceNumber = $referenceNumber;
        $this->orderName = $orderName;
        $this->companyName = $companyName;
        $this->total = $total;
        $this->formulationName = $formulationName;
        $this->format = $format;
        $this->formulationWeight = $formulationWeight . ' mg/L';
        $this->predictedOsmolality = $predictedOsmolality . ' mOsm/kg';
        $this->cgmp = $cgmp;
        $this->totalLiters = $totalLiters . ' L';
        $this->leadTime = $leadTime . ' Weeks';
        $this->isSpecialized = $isSpecialized;
        $this->orderLink = config('app.lux_url') . '/media-configurator?type=order&id=' . $referenceNumber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Order Transfer Notification | # " . $this->referenceNumber)->view($this->getTemplateName())
            ->with([
                'referenceNumber' => $this->referenceNumber,
                'formulationName' => $this->formulationName,
                'companyName' => $this->companyName,
                'total' => $this->total,
                'format' => $this->format,
                'formulationWeight' => $this->formulationWeight,
                'predictedOsmolality' => $this->predictedOsmolality,
                'cgmp' => $this->cgmp,
                'totalLiters' => $this->totalLiters,
                'leadTime' => $this->leadTime,
                'isSpecialized' => $this->isSpecialized,
                'orderLink' => $this->orderLink
            ]);
    }
}
