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
use Carbon\Carbon;

class QuoteReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $quote, $expiration_date, $amount_of_days_until_expired, $customer, $direct_accept_quote_text, $link_to_logo_image, $link_to_website, $information_contact_text, $link_to_contact_page, $email_template_color, $business_kvk, $subject;
    
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
        $utm_code = '?utm_source=crm&utm_medium=email&utm_campaign=quote_reminder';
        $this->expiration_date = $this->quote->expiration_date;
        $this->amount_of_days_until_expired = Carbon::parse($this->expiration_date)->startOfDay()->diffInDays(Carbon::now()->startOfDay());
        $this->customer = $this->quote->customer;
        $this->direct_accept_quote_text = Setting::where('name','direct_accept_quote_text')->first()->value;
        $this->link_to_logo_image = Setting::where('name','link_to_logo_image')->first()->value;
        $this->link_to_website = Setting::where('name','link_to_website')->first()->value . $utm_code;
        $this->information_contact_text = Setting::where('name','information_contact_text')->first()->value;
        $this->link_to_contact_page = Setting::where('name','link_to_contact_page')->first()->value . $utm_code;
        $this->email_template_color = Setting::where('name','email_template_color')->first()->value;
        $this->business_kvk = Setting::where('name','business_kvk')->first()->value;
        $this->subject = 'Herinnering: Offerte ' . lcfirst($this->quote->title) . ' is nog ' . $this->amount_of_days_until_expired . ' dagen geldig';
        if ($this->amount_of_days_until_expired < 5) {
            $this->subject = 'Let op: offerte ' . lcfirst($this->quote->title) . ' is nog maar ' . $this->amount_of_days_until_expired . ' dagen geldig';
        } elseif ($this->amount_of_days_until_expired == 1) {
            $this->subject = 'Let op: offerte ' . lcfirst($this->quote->title) . ' is nog maar ' . $this->amount_of_days_until_expired . ' dag geldig';
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->markdown('mail.quote_mail_reminder');
    }
}
