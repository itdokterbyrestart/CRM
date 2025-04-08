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

class QuoteMailConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $quote, $status, $type, $link_to_logo_image, $link_to_website, $link_to_contact_page, $email_template_color, $business_kvk, $quote_mail_accepted_button_text, $quote_mail_accepted_text_block_one_title, $quote_mail_accepted_text_block_one_text, $quote_mail_accepted_text_block_two_title, $quote_mail_accepted_text_block_two_text;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quote_id)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->afterCommit();

        $utm_code = '?utm_source=crm&utm_medium=email&utm_campaign=quote_accepted';
        $this->quote = Quote::with('quote_statuses')->findOrFail($quote_id);
        $this->status = $this->quote->quote_statuses->first();
        $this->type = ($this->status->name == 'Akkoord' ? 'accepted' : 'refused');
        $this->link_to_logo_image = Setting::where('name','link_to_logo_image')->first()->value;
        $this->link_to_website = Setting::where('name','link_to_website')->first()->value . $utm_code;
        $this->link_to_contact_page = Setting::where('name','link_to_contact_page')->first()->value . $utm_code;
        $this->email_template_color = Setting::where('name','email_template_color')->first()->value;
        $this->business_kvk = Setting::where('name','business_kvk')->first()->value;
        $this->quote_mail_accepted_button_text = Setting::where('name','quote_mail_accepted_button_text')->first()->value;
        $this->quote_mail_accepted_text_block_one_title = Setting::where('name','quote_mail_accepted_text_block_one_title')->first()->value;
        $this->quote_mail_accepted_text_block_one_text = Setting::where('name','quote_mail_accepted_text_block_one_text')->first()->value;
        $this->quote_mail_accepted_text_block_two_title = Setting::where('name','quote_mail_accepted_text_block_two_title')->first()->value;
        $this->quote_mail_accepted_text_block_two_text = Setting::where('name','quote_mail_accepted_text_block_two_text')->first()->value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bevestiging offerte ' . lcfirst($this->quote->title))->markdown('mail.quote_mail_' . $this->type);
    }
}
