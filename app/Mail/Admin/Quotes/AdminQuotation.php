<?php

namespace App\Mail\Admin\Quotes;

use App\Traits\MultiTenantTemplateable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminQuotation extends Mailable
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
    protected $quoteLink;
    protected $notes;
    protected $billingAddress;
    protected $shippingAddress;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $orderName, $customerName, $companyName, $total, $formulationName, $format, $formulationWeight, $predictedOsmolality, $cgmp, $totalLiters, $leadTime, $notes, $billingAddress, $shippingAddress)
    {
        $this->tenant_template = '.admin.quotes.admin_quote';
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
        $this->notes = $notes;
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
        $this->quoteLink = config('app.admin_url') . '/orders/quoteDetails?id=' . $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("New Quote Requested")->view($this->getTemplateName())
            ->with([
                'formulationName' => $this->formulationName,
                'referenceNumber' => $this->referenceNumber,
                'customerName' => $this->customerName,
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
                'quoteLink' => $this->quoteLink
            ]);
    }
}
