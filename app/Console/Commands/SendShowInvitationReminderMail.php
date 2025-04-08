<?php

namespace App\Console\Commands;

use App\Mail\{
    ScheduleAppointmentMail,
    ScheduleAppointmentReminderMailToSelf,
};
use Illuminate\Console\Command;

use App\Models\{
    Order,
    OrderStatus,
    Setting,
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendShowInvitationReminderMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_show_invitation_reminder_mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder to plan an appointment on set periods of time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $amount_of_days_before_show = str_replace(' ', '', explode(',', Setting::where('name','send_show_invitation_reminder_mail_period')->first()->value));
        
        $orders = Order::where('order_status_id', OrderStatus::where('name','Uitnodiging gestuurd')->first()->id)->get();
        $business_email = Setting::where('name','business_email')->first()->value ?? 'info@deitdokter.nl';

        foreach ($orders as $order) {
            foreach ($amount_of_days_before_show as $amount_of_days) {
                if (Carbon::parse($order->created_at)->subDays($amount_of_days)->startOfDay() == Carbon::now()->startOfDay()) {
                    if (min($amount_of_days_before_show) == $amount_of_days) {
                        Mail::to($business_email)
                            ->queue(new ScheduleAppointmentReminderMailToSelf($order->id));
                    }

                    Mail::to($order->customer->email ?? $business_email)
                        ->queue(new ScheduleAppointmentMail($order->id, (min($amount_of_days_before_show) == $amount_of_days ? 2 : 1),0));
                }
            }
        }
        
        return 0;
    }
}
