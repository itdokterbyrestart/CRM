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

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invoice, $customer, $direct_accept_quote_text, $link_to_logo_image, $link_to_website, $information_contact_text, $link_to_contact_page, $email_template_color, $invoice_text, $business_kvk;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice_id)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->afterCommit();
        
        $utm_code = '?utm_source=crm&utm_medium=email&utm_campaign=invoice';
        $this->invoice = Invoice::findOrFail($invoice_id);
        $this->customer = $this->invoice->order->customer;
        $this->invoice_text = Setting::where('name','invoice_text')->first()->value;
        $this->link_to_logo_image = Setting::where('name','link_to_logo_image')->first()->value;
        $this->link_to_website = Setting::where('name','link_to_website')->first()->value . $utm_code;
        $this->link_to_contact_page = Setting::where('name','link_to_contact_page')->first()->value . $utm_code;
        $this->email_template_color = Setting::where('name','email_template_color')->first()->value;
        $this->business_kvk = Setting::where('name','business_kvk')->first()->value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name') . ' - Factuur ' . $this->invoice->invoice_number)->markdown('mail.invoice_mail');
    }
}
