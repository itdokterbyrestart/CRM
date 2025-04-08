<?php

namespace App\Mail;

use App\Models\{
    Prijsopgave,
};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrijsopgaveContactInfoToBusinessMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $prijsopgave, $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($prijsopgave_id)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->prijsopgave = Prijsopgave::find($prijsopgave_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nieuwe onafgeronde aanvraag voor een DJ')->markdown('mail.prijsopgave_contact_info_to_business_mail');
    }
}
