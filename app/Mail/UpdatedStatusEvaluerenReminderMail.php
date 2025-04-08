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

class UpdatedStatusEvaluerenReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $orders;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        setlocale(LC_ALL, 'nl_NL');

       // Get list of orders where order_status is Optreden gepland and created_at is 14 days from now
       $this->orders = Order::whereHas('order_status', function ($q) {
            $q->where('name','Evalueren');
        })
        ->whereBetween('created_at',[Carbon::now()->startOfWeek()->subDays(7), Carbon::now()->startOfWeek()])
        ->get();

        if ($this->orders->count() === 0) {
            return 0;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Herinnering evalueren shows')->markdown('mail.updated_status_evalueren_reminder')->with('orders', $this->orders);
    }
}
