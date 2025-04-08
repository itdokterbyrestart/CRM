<?php

namespace App\Console\Commands;

use App\Models\{
    Order,
    OrderStatus,
    Setting,
};

use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;
use App\Mail\{
    UpdatedStatusUitnodigingGestuurdReminderMail,
    ScheduleAppointmentMail,
};
use Illuminate\Console\Command;

class ChangeOrderStatusBeforeShowAndSendSchedulingMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change_order_status_before_show_and_send_scheduling_mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changes the order status for each order where order status equals optreden gepland to bellen if created at is less than 21 days away and send automatic scheduling email';

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
        $business_email = Setting::where('name','business_email')->first()->value ?? 'info@deitdokter.nl';
        
        // Get list of orders where order_status is Optreden gepland and created_at is between 14 and 21 days from now
        $orders = Order::whereHas('order_status', function ($q) {
            $q->whereNotIn('name',['Afspraak gemaakt','Alles geregeld','Verloren','Gratis','Betaald, archief','Wachten op datum']);
        })
        ->whereBetween('created_at',[Carbon::now()->startOfWeek()->addDays(21), Carbon::now()->startOfWeek()->addDays(28)])
        ->get();

        // Set orderstatus id of Uitnodiging gestuurd
        $orderstatus_id = OrderStatus::where('name', 'Uitnodiging gestuurd')->first()->id ?? (OrderStatus::first()->id);

        // Foreach order attach the new order status
        foreach ($orders as $order) {
            $order->update([
                'order_status_id' => $orderstatus_id,
            ]);
            // Send automatic scheduling mail
            Mail::to($order->customer->email)
                ->queue(new ScheduleAppointmentMail($order->id,0,0));
        }
        
        if ($orders->count() > 0) {
        // Send services reminder mail
            Mail::to($business_email)
                ->queue(new UpdatedStatusUitnodigingGestuurdReminderMail());
        }
        
        // Command done
        return 0;
    }
}
