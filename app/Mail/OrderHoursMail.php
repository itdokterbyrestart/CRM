<?php

namespace App\Mail;

use App\Models\{
    OrderHour,
    User,
};
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderHoursMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $orderHours;
    public $start_date, $end_date, $week_number;
    public $user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        setlocale(LC_ALL, 'nl_NL');

        $this->user = User::findOrFail($user_id);
        $this->start_date = Carbon::now()->startOfWeek()->subWeeks(1);
        $this->end_date = Carbon::now()->startOfWeek()->subSecond(1);
        $this->week_number = $this->start_date->translatedFormat('W');
        
        $this->orderHours = OrderHour::query()
            ->whereBetween('date', [$this->start_date,$this->end_date])
            ->where('user_id', $this->user->id)
            ->with('order.customer')
            ->get();

        $this->start_date = $this->start_date->translatedFormat('l d F Y');
        $this->end_date = $this->end_date->translatedFormat('l d F Y');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Urenrapport ' . $this->user->name . ', week ' . $this->week_number)->markdown('mail.order_hours_user_report_mail');
    }
}
