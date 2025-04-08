<?php

namespace App\Mail;

use App\Models\{
    Quote,
    Setting,
};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $quote, $customer, $direct_accept_quote_text, $link_to_logo_image, $link_to_website, $information_contact_text, $link_to_contact_page, $email_template_color, $business_kvk, $business_email, $business_phone, $business_whatsapp_link;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quote_id)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->afterCommit();


        $this->quote = Quote::findOrFail($quote_id);
        $this->customer = $this->quote->customer;
        $utm_code = '?utm_source=crm&utm_medium=email&utm_campaign=quote';
        $this->direct_accept_quote_text = Setting::where('name','direct_accept_quote_text')->first()->value;
        $this->link_to_logo_image = Setting::where('name','link_to_logo_image')->first()->value;
        $this->link_to_website = Setting::where('name','link_to_website')->first()->value . $utm_code;
        $this->information_contact_text = Setting::where('name','information_contact_text')->first()->value;
        $this->link_to_contact_page = Setting::where('name','link_to_contact_page')->first()->value . $utm_code;
        $this->email_template_color = Setting::where('name','email_template_color')->first()->value;
        $this->business_kvk = Setting::where('name','business_kvk')->first()->value;
        $this->business_email = Setting::where('name','business_email')->first()->value;
        $this->business_phone = Setting::where('name','business_phone')->first()->value;
        $this->business_whatsapp_link = Setting::where('name','business_whatsapp_link')->first()->value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Offerte ' . lcfirst($this->quote->title))->markdown('mail.quote_mail');
    }
}
