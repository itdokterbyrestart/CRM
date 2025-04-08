<?php

namespace App\Mail;

use App\Models\{
    Order,
};
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleAppointmentReminderMailToSelf extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order, $customer, $date;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->order = Order::findOrFail($order_id);
        $this->customer = $this->order->customer;
        (string)$this->date = Carbon::parse($this->order->created_at)->format('d-m-Y');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Er is nog geen afspraak gepland voor het feest op ' . $this->date . ' van ' . $this->customer->name)->markdown('mail.schedule_appointment_reminder_to_self');
    }
}
