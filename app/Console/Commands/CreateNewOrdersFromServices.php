<?php

namespace App\Console\Commands;

use App\Models\{
    Order,
    Customer,
    OrderStatus,
    OrderProduct,
    Setting,
};

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use App\Mail\ServicesReminderMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApkInvitationMail;

use Illuminate\Console\Command;

class CreateNewOrdersFromServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create_new_orders_from_services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new order for every customer that has a service in the month of execution. If there are any it also sends an email reminder that new services have been added. ';

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
        
        // Get list of customers with services in current month
        $customers = Customer::query()
            ->whereHas('services', function ($q) {
                $q->where('month',Carbon::now()->month);
            })
            ->with('services', function ($q) {
                $q->where('month',Carbon::now()->month)->with('product');
            })
            ->get();

        // Set orderstatus id of Factureren
        $orderstatus_id = OrderStatus::where('name', 'Factureren')->first()->id ?? (OrderStatus::first()->id);

        // Set orderstatus for invited
        $orderstatus_invited = OrderStatus::where('name', 'Uitnodiging gestuurd')->first()->id ?? (OrderStatus::first()->id);

        // Foreach customer create order
        foreach ($customers as $customer) {
            
            // Title for the new order of the customer. If customer has more than 1 service the title for the order is generated, else it is the name of the service
            if ($customer->services->count() >= 1) {
                $title = '';
                $key = 0;
                $max_count = $customer->services->count();
                foreach ($customer->services as $service) {
                    $key++;
                    $title = $title . $service->name . ($key === ($max_count - 1) ? ' en ' : ($key === $max_count ? '' : ', '));
                }
            } else {
                $title = $customer->services->first()->name;
            }

            // Create order
            $order = Order::create([
                'title' => $title,
                'description' => 'Gegenereerd op ' . Carbon::now()->format('d-m-Y'),
                'order_status_id' => $orderstatus_id,
                'customer_id' => $customer->id,
                'created_at' => Carbon::now()->firstOfMonth(),
                'total_price_customer_excluding_tax' => 0,
                'total_tax_amount' => 0,
                'total_price_customer_including_tax' => 0,
                'total_purchase_price_excluding_tax' => 0,
                'total_profit' => 0,
            ]);

            // Add orderproducts to order and invite for APK if applicable

            $array_total_price_customer_excluding_tax = [];
            $array_total_tax_amount = [];
            $array_total_price_customer_including_tax = [];
            $array_total_purchase_price_excluding_tax = [];
            $array_total_profit = [];
            $product_order = 1;


            foreach ($customer->services as $service) {
                $product = $service->product;
                // Continue of product is not set for service
                if ($product == null) {
                    continue;
                }

                // Send invitation mail for the APK
                if ($service->name === 'APK aan huis' OR $service->name === 'APK op afstand') {
                    Mail::to($customer->email ?? $business_email)
                        ->queue(new ApkInvitationMail($customer->id));

                    $order->update([
                        'order_status_id' => $orderstatus_invited,
                    ]);
                }

                if ($service->name === 'Backup') {
                    $description = 'Looptijd: ' . Carbon::now()->startOfMonth()->format('d-m-Y') . ' - ' . Carbon::now()->addMonth(11)->endOfMonth()->format('d-m-Y');
                } else {
                    $description = null;
                }

                $total_purchase_price_excluding_tax_product = ($product->purchase_price_excluding_tax) ?? 0;
                $revenue = ($product->price_customer_excluding_tax) ?? 0;
                $profit = $revenue - $total_purchase_price_excluding_tax_product;

                // Create order product
                OrderProduct::create([
                    'name' => $product->name,
                    'description' => $product->description,
                    'purchase_price_excluding_tax' => $product->purchase_price_excluding_tax ?? 0,
                    'purchase_price_including_tax' => $product->purchase_price_including_tax ?? 0,
                    'price_customer_excluding_tax' => $product->price_customer_excluding_tax ?? 0,
                    'price_customer_including_tax' => $product->price_customer_including_tax ?? 0,
                    'amount' => 1,
                    'revenue' => $revenue ?? 0,
                    'profit' => $profit ?? 0,
                    'total_price_customer_including_tax' => $product->price_customer_including_tax ?? 0,
                    'tax_percentage' => $product->tax_percentage ?? 21,
                    'order_id' => $order->id,
                    'total_purchase_price_excluding_tax' => $purchase_price_excluding_tax_product ?? 0,
                    'description' => $description,
                    'order' => $product_order,
                ]);

                $array_total_price_customer_excluding_tax[] = number_format((float)$revenue, 2, '.', '');
                $array_total_tax_amount[] = number_format((float)$product->price_customer_including_tax - (float)$revenue, 2, '.', '');
                $array_total_price_customer_including_tax[] = number_format((float)$product->price_customer_including_tax, 2, '.', '');
                $array_total_purchase_price_excluding_tax[] = number_format((float)$total_purchase_price_excluding_tax_product ?? 0, 2, '.', '');
                $array_total_profit[] = number_format((float)$profit, 2, '.', '');
            }
            
            $order->update([
                'total_price_customer_excluding_tax' => number_format(array_sum($array_total_price_customer_excluding_tax), 2, '.', ''),
                'total_tax_amount' => number_format(array_sum($array_total_tax_amount), 2, '.', ''),
                'total_price_customer_including_tax' => number_format(array_sum($array_total_price_customer_including_tax), 2, '.', ''),
                'total_purchase_price_excluding_tax' => number_format(array_sum($array_total_purchase_price_excluding_tax), 2, '.', ''),
                'total_profit' => number_format(array_sum($array_total_profit), 2, '.', ''),
            ]);
        }
        
        // Send services reminder mail
        Mail::to($business_email)
            ->queue(new ServicesReminderMail());
        
        // Command done
        return 0;
    }
}
