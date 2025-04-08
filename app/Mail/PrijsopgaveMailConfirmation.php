<?php

namespace App\Mail;

use App\Models\{
    Quote,
};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrijsopgaveMailConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $quote, $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quote_id)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->quote = Quote::find($quote_id);
        $this->customer = $this->quote->customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nieuwe aanvraag voor een DJ')->markdown('mail.prijsopgave_mail_confirmation');
    }
}
