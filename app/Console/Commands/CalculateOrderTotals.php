<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    Order,
};
use Illuminate\Database\Eloquent\Collection;

class CalculateOrderTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_order_totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates totals for total_price_customer_excluding_tax, total_tax_amount, total_price_customer_including_tax, total_purchase_price_excluding_tax and total_profit';

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
        Order::with('order_products', 'order_hours')->chunk(100, function (Collection $orders) {
            foreach ($orders as $order) {
                $total_price_customer_excluding_tax = 0;
                $total_tax_amount = 0;
                $total_price_customer_including_tax = 0;
                $total_purchase_price_excluding_tax = 0;
                $total_profit = 0;

                // Loop over all order products
                foreach ($order->order_products as $orderproduct) {
                    $total_price_customer_excluding_tax = $total_price_customer_excluding_tax + $orderproduct->total_price_customer_excluding_tax;
                    $total_tax_amount = $total_tax_amount + ($orderproduct->total_price_customer_including_tax - $orderproduct->profit);
                    $total_price_customer_including_tax = $total_price_customer_including_tax + $orderproduct->total_price_customer_including_tax;
                    $total_purchase_price_excluding_tax = $total_purchase_price_excluding_tax + $orderproduct->total_purchase_price_excluding_tax;
                    $total_profit = $total_profit + $orderproduct->profit;
                }
                
                // Loop over all order hours
                foreach ($order->order_hours as $orderhour) {
                    $total_price_customer_excluding_tax = $total_price_customer_excluding_tax + $orderhour->amount_revenue_excluding_tax;
                    $total_tax_amount = $total_tax_amount + ($orderhour->amount_revenue_including_tax - $orderhour->amount_revenue_excluding_tax);
                    $total_price_customer_including_tax = $total_price_customer_including_tax + $orderhour->amount_revenue_including_tax;
                    $total_profit = $total_profit + $orderhour->amount_revenue_excluding_tax;
                }

                $order->update([
                    'total_price_customer_excluding_tax' => $total_price_customer_excluding_tax,
                    'total_tax_amount' => $total_tax_amount,
                    'total_price_customer_including_tax' => $total_price_customer_including_tax,
                    'total_purchase_price_excluding_tax' => $total_purchase_price_excluding_tax,
                    'total_profit' => $total_profit,
                ]);
            }
        });
        
        return 0;
    }
}
