<?php

namespace App\Mail;

use App\Models\{
    Invoice,
    InvoiceStatus,
    Setting,
};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invoice, $customer, $status, $link_to_logo_image, $link_to_website, $link_to_contact_page, $email_template_color, $business_kvk, $invoice_reminder_text;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice_id)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->afterCommit();

        $this->invoice = Invoice::with('invoice_statuses')->findOrFail($invoice_id);
        $this->customer = $this->invoice->order->customer;
        $this->status = $this->invoice->invoice_statuses->whereIn('name',['Herinnering 1', 'Herinnering 2', 'Herinnering 3'])->first();
        if ($this->status) {
            $this->status = $this->status->name;
        } else {
            $this->status = InvoiceStatus::where('name','Herinnering 1')->first()->name;
        }

        $utm_code = '?utm_source=crm&utm_medium=email&utm_campaign=invoice_reminder';
        $this->link_to_logo_image = Setting::where('name','link_to_logo_image')->first()->value;
        $this->link_to_website = Setting::where('name','link_to_website')->first()->value . $utm_code;
        $this->link_to_contact_page = Setting::where('name','link_to_contact_page')->first()->value . $utm_code;
        $this->email_template_color = Setting::where('name','email_template_color')->first()->value;
        $this->business_kvk = Setting::where('name','business_kvk')->first()->value;
        $this->invoice_reminder_text = Setting::where('name','invoice_reminder_text')->first()->value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name') . ' - ' . ($this->status == 'Herinnering 1' ? 'Herinnering' : $this->status) . ' voor factuur ' . $this->invoice->invoice_number)->markdown('mail.invoice_reminder_mail');
    }
}
