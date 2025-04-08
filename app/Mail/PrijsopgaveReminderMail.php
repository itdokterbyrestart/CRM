<?php

namespace App\Mail;

use App\Models\{
    Prijsopgave,
    Setting,
};
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrijsopgaveReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $prijsopgave, $reminder, $link_to_logo_image, $link_to_website, $link_to_contact_page, $email_template_color, $business_kvk, $email_header, $email_header_title, $email_header_subtitle;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($prijsopgave_id, $reminder)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->prijsopgave = Prijsopgave::findOrFail($prijsopgave_id);
        $this->reminder = $reminder;



        if ($this->reminder == 1) {
            $this->email_header = 'Vergeet je prijsopgave niet! Ontvang binnen 2 minuten de prijs voor jouw feest.';
            $this->email_header_title = 'Maak je prijsopgave af en ontvang binnen 2 minuten jouw prijs!';
            $this->email_header_subtitle = 'Benieuwd hoeveel DJ T-Fooh kost op jouw feest?';
        } elseif ($this->reminder == 2) {
            $this->email_header = 'Maak je prijsopgave af! ' . Carbon::parse($this->prijsopgave->party_date)->translatedFormat('j F Y') . ' is nog beschikbaar: wees er snel bij!';
            $this->email_header_title = Carbon::parse($this->prijsopgave->party_date)->translatedFormat('j F Y') . ' is nog beschikbaar';
            $this->email_header_subtitle = 'Benieuwd hoeveel DJ T-Fooh kost op jouw feest?';
        } elseif ($this->reminder == 3) {
            $this->email_header = 'Korte reminder: maak je prijsopgave af en ontvang binnen 2 minuten de prijs voor jouw feest.';
            $this->email_header_title = 'Binnen 2 minuten een prijs voor jouw feest!';
            $this->email_header_subtitle = 'Benieuwd hoeveel DJ T-Fooh kost op jouw feest?';
        } elseif ($this->reminder == 4) {
            $this->email_header = 'Laatste kans: binnen 2 minuten de prijs voor jouw feest.';
            $this->email_header_title = 'Maak nu je prijsopgave af';
            $this->email_header_subtitle = 'Dit is mijn laatste berichtje.';
        }
        
        $this->link_to_logo_image = Setting::where('name','link_to_logo_image')->first()->value;
        $this->link_to_website = Setting::where('name','link_to_website')->first()->value;
        $this->link_to_contact_page = Setting::where('name','link_to_contact_page')->first()->value;
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
        return $this->subject($this->email_header)->markdown('mail.prijsopgave_reminder_mail');
    }
}
