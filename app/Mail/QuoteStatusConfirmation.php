<?php

namespace App\Mail;

use App\Models\{
    Quote,
};
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteStatusConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $quote, $customer, $status, $date, $clientIP;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quote_id, $clientIP)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->afterCommit();

        $this->quote = Quote::with('quote_statuses','customer')->findOrFail($quote_id);
        $this->customer = $this->quote->customer;
        $this->status = $this->quote->quote_statuses->first();
        $this->date = Carbon::parse($this->status->pivot->created_at)->format('d-m-Y | H:i');
        $this->clientIP = $clientIP ?? 'Onbekend';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Wijziging offerte ' . $this->customer->name)->markdown('mail.quote_status_confirmation');
    }
}
