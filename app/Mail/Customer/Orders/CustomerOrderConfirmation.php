<?php

namespace App\Mail\Customer\Orders;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerOrderConfirmation extends Mailable
{
    use Queueable, SerializesModels, MultiTenantTemplateable;


    protected $orderName;
    protected $referenceNumber;
    protected $customerName;
    protected $companyName;
    protected $total;
    protected $formulationName;
    protected $format;
    protected $formulationWeight;
    protected $predictedOsmolality;
    protected $cgmp;
    protected $totalLiters;
    protected $leadTime;
    protected $notes;
    protected $billingAddress;
    protected $shippingAddress;
    protected $orderLink;
    protected $isPriceHidden;
    protected $isSpecialized;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($referenceNumber, $orderName, $customerName, $companyName, $total, $formulationName, $format, $formulationWeight, $predictedOsmolality, $cgmp, $totalLiters, $leadTime, $notes, $billingAddress, $shippingAddress, $isPriceHidden, $isSpecialized)
    {
        $this->tenant_template = '.customer.orders.order_customer';
        $this->referenceNumber = $referenceNumber;
        $this->orderName = $orderName;
        $this->customerName = $customerName;
        $this->companyName = $companyName;
        $this->total = '$' . $total;
        $this->formulationName = $formulationName;
        $this->format = $format;
        $this->formulationWeight = $formulationWeight . ' mg/L';
        $this->predictedOsmolality = $predictedOsmolality . ' mOsm/kg';
        $this->cgmp = $cgmp;
        $this->totalLiters = $totalLiters . ' L';
        $this->leadTime = $leadTime . ' Weeks';
        $this->notes = $notes;
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
        $this->isPriceHidden = $isPriceHidden;
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
        return $this->subject("Order Placed Successfully | # " . $this->referenceNumber)->view($this->getTemplateName())
            ->with([
                'customerName' => $this->customerName,
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
                'notes' => $this->notes,
                'billingAddress' => $this->billingAddress,
                'shippingAddress' => $this->shippingAddress,
                'isPriceHidden' => $this->isPriceHidden,
                'isSpecialized' => $this->isSpecialized,
                'orderLink' => $this->orderLink
            ]);
    }
}
