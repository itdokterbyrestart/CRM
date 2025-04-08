<?php

namespace App\Livewire\Frontend\Invoice;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Invoice,
};
use Mollie\Laravel\Facades\Mollie;
use App\Models\Setting;


class Payment extends Component
{
    public $invoice_id, $invoice, $invoice_status, $payment_status;
    public $transaction_costs;
    public $invoice_statuses;

    public $selectedCustomer;

    public function booted()
    {
        $this->invoice = Invoice::with('invoice_statuses')->findOrFail($this->invoice_id);
        $this->transaction_costs = Setting::where('name','transaction_costs_invoice')->first()->value ?? '0.39';
        $this->invoice_status = $this->invoice->invoice_statuses->first();
        if (!is_null($this->invoice->payment_id)) {
            $payment = Mollie::api()->payments->get($this->invoice->payment_id);
            if($payment->isPaid()) {
                $this->payment_status = 'paid';
            }
            if($payment->isOpen()) {
                $this->payment_status = 'open';
            }
            if ($payment->isFailed()) {
                $this->payment_status = 'failed';
            }
            if ($payment->isCanceled()) {
                $this->payment_status = 'canceled';
            }
            if ($payment->isExpired()) {
                $this->payment_status = 'expired';
            }
        }
    }

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function render()
    {
        return view('frontend.invoice.livewire.payment');
    }

    public function preparePayment()
    {     
        if (!is_null($this->invoice->payment_id)) {
            $payment = Mollie::api()->payments->get($this->invoice->payment_id);

            if ($payment->isPaid()) {
                return $this->dispatch('refresh')->self();
            }

            if($payment->isOpen()) {
                $create_new_payment = false;
            }
            if ($payment->isFailed()) {
                $create_new_payment = true;
            }
            if ($payment->isCanceled()) {
                $create_new_payment = true;
            }
            if ($payment->isExpired()) {
                $create_new_payment = true;
            }
        } else {
            $create_new_payment = true;
        }
            
        if ($create_new_payment == true) {
            $payment = Mollie::api()->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => (string)((float)$this->invoice->total_price_customer_including_tax + (float)$this->transaction_costs) // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                "description" => config('app.name') . " - Factuur " . $this->invoice->invoice_number,
                "redirectUrl" => route('invoice.customer.show', $this->invoice->id),
                "webhookUrl" => route('webhooks.mollie'),
                "metadata" => [
                    "invoice_id" => $this->invoice->id,
                ],
            ]);
    
            $this->invoice->update(
                ['payment_id' => $payment->id]
            );
        }
        // redirect customer to Mollie checkout page
        return redirect($payment->getCheckoutUrl(), 303);
    }
}
