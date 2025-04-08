<?php

namespace App\Mail;

use App\Models\{
    Customer,
};
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServicesReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $customers, $month;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->customers = Customer::query()
        ->whereHas('services', function ($q) {
            $q->where('month', Carbon::now()->month);
        })
        ->get();

        if (count($this->customers) === 0) {
            return 0;
        }

        $this->month = strtolower(__('dates.' . Carbon::now()->format('F')));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Herinnering openstaande services')->markdown('mail.services_reminder')->with('customers', $this->customers);
    }
}
