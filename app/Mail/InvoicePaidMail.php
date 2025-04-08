<?php

namespace App\Mail;

use App\Models\{
    Invoice,
    Setting,
};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Mollie\Laravel\Facades\Mollie;


class InvoicePaidMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invoice, $customer, $payment, $link_to_logo_image, $link_to_website, $link_to_contact_page, $email_template_color, $business_kvk, $invoice_paid_text, $transaction_costs;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice_id, $payment_id)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->afterCommit();

        $this->invoice = Invoice::findOrFail($invoice_id);
        $this->payment = Mollie::api()->payments->get($payment_id);
        $this->customer = $this->invoice->order->customer;

        $utm_code = '?utm_source=crm&utm_medium=email&utm_campaign=invoice_paid';
        $this->link_to_logo_image = Setting::where('name','link_to_logo_image')->first()->value;
        $this->link_to_website = Setting::where('name','link_to_website')->first()->value . $utm_code;
        $this->link_to_contact_page = Setting::where('name','link_to_contact_page')->first()->value . $utm_code;
        $this->email_template_color = Setting::where('name','email_template_color')->first()->value;
        $this->business_kvk = Setting::where('name','business_kvk')->first()->value;
        $this->invoice_paid_text = Setting::where('name','invoice_paid_text')->first()->value;
        $this->transaction_costs = (Setting::where('name','transaction_costs_invoice')->first()->value ?? '0.39');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name') . ' - Betaald: Factuur ' . $this->invoice->invoice_number)->markdown('mail.invoice_paid_mail');
    }
}
