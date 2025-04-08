<?php

namespace App\Console\Commands;

use App\Mail\ApkReminderMail;
use Illuminate\Console\Command;

use App\Models\{
    Order,
    OrderStatus,
    Setting,
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendAPKReminderMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_apk_reminder_mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an APK reminder mail';

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
        $amount_of_days_after_creating_services_array = str_replace(' ', '', explode(',', Setting::where('name','send_apk_reminder_mail_period')->first()->value));
        
        $orders = Order::where('order_status_id', OrderStatus::where('name','Uitnodiging gestuurd')->first()->id)->get();
        $business_email = Setting::where('name','business_email')->first()->value ?? 'info@deitdokter.nl';

        foreach ($orders as $order) {
            foreach ($amount_of_days_after_creating_services_array as $amount_of_days) {
                if (Carbon::parse($order->created_at)->addDays($amount_of_days)->startOfDay() == Carbon::now()->startOfDay()) {
                    Mail::to($order->customer->email ?? $business_email)
                        ->queue(new ApkReminderMail($order->customer->id,(max($amount_of_days_after_creating_services_array) == $amount_of_days ? 1 : 0)));
                }
            }
        }
        
        return 0;
    }
}
