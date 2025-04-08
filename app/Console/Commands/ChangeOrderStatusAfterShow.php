<?php

namespace App\Console\Commands;

use App\Models\{
    Order,
    OrderStatus,
    Setting,
};

use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;
use App\Mail\UpdatedStatusEvaluerenReminderMail;
use Illuminate\Console\Command;

class ChangeOrderStatusAfterShow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change_order_status_after_show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changes the order status for each order to evaluate if created was last week.';

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
        
        // Get list of orders from previous week
        $orders = Order::whereBetween('created_at',[Carbon::now()->startOfWeek()->subDays(7), Carbon::now()->startOfWeek()])
        ->get();

        // Set orderstatus id of Evalueren
        $orderstatus_id = OrderStatus::where('name', 'Evalueren')->first()->id ?? (OrderStatus::first()->id);

        // Foreach order attach the new order status
        foreach ($orders as $order) {
            $order->update([
                'order_status_id' => $orderstatus_id,
            ]);
        }
        
        if ($orders->count() > 0) {
        // Send services reminder mail
            Mail::to($business_email)
            ->queue(new UpdatedStatusEvaluerenReminderMail());
        }
        
        // Command done
        return 0;
    }
}
