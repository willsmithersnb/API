<?php

namespace App\Mail\Customer\Quotes;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerQuotation extends Mailable
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
    protected $quoteLink;
    protected $billingAddress;
    protected $shippingAddress;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $orderName, $customerName, $companyName, $total, $formulationName, $format, $formulationWeight, $predictedOsmolality, $cgmp, $totalLiters, $leadTime, $notes, $billingAddress, $shippingAddress)
    {
        $this->tenant_template = '.customer.quotes.customer_quote';
        $this->orderName = $orderName;
        $this->referenceNumber = $id;
        $this->customerName = $customerName;
        $this->companyName = $companyName;
        $this->total = '$'.$total;
        $this->formulationName = $formulationName;
        $this->format = $format;
        $this->formulationWeight = $formulationWeight . ' mg/L';
        $this->predictedOsmolality = $predictedOsmolality . ' mOsm/kg';
        $this->cgmp = $cgmp;
        $this->totalLiters = $totalLiters . ' L';
        $this->leadTime = $leadTime . ' Weeks';
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
        $this->notes = $notes;
        $this->quoteLink = config('app.lux_url') . '/media-configurator?type=quotes&id=' . $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Quote Placed Successfully!")->view($this->getTemplateName())
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
                'billingAddress' => $this->billingAddress,
                'shippingAddress' => $this->shippingAddress,
                'notes' => $this->notes,
                'quoteLink' => $this->quoteLink
            ]);
    }
}
