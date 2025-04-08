<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    Order,
};
use Illuminate\Support\Facades\DB;

class CalculateOrderPricesAndProfitsAndTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_order_prices_and_profits_and_totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate price customer excluding and including tax, calculate total tax amount, calculate total purchase price excluding tax and calculate total profit for all orders.';

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
        // Save or update order information
        $orders = Order::with('order_products','order_hours')->get();

        DB::transaction(function () use ($orders) {
            foreach ($orders as $order) {
                $array_total_price_customer_excluding_tax = [];
                $array_total_tax_amount = [];
                $array_total_price_customer_including_tax = [];
                $array_total_purchase_price_excluding_tax = [];
                $array_total_profit = [];

                $total_price_customer_excluding_tax = 0;
                $total_tax_amount = 0;
                $total_price_customer_including_tax = 0;
                $total_purchase_price_excluding_tax = 0;
                $total_profit = 0;

                // Loop over all order products
                foreach ($order->order_products as $orderproduct) {
                    // For each product add prices including and excluding tax to an array
                    $array_total_price_customer_excluding_tax[] = number_format((float)$orderproduct->revenue, 2, '.', '');
                    $array_total_tax_amount[] = number_format((float)$orderproduct->total_price_customer_including_tax - (float)$orderproduct->revenue, 2, '.', '');
                    $array_total_price_customer_including_tax[] = number_format((float)$orderproduct->total_price_customer_including_tax, 2, '.', '');
                    $array_total_purchase_price_excluding_tax[] = number_format((float)$orderproduct->total_purchase_price_excluding_tax, 2, '.', '');
                    $array_total_profit[] = number_format((float)$orderproduct->profit, 2, '.', '');
                }
 
 
                // Loop over all order hours
                foreach ($order->order_hours as $orderhour) {
                    // For each hour add prices including and excluding tax to an array
                    $array_total_price_customer_excluding_tax[] = number_format((float)$orderhour->amount_revenue_excluding_tax, 2, '.', '');
                    $array_total_tax_amount[] = number_format((float)$orderhour->amount_revenue_including_tax - (float)$orderhour->amount_revenue_excluding_tax, 2, '.', '');
                    $array_total_price_customer_including_tax[] = number_format((float)$orderhour->amount_revenue_including_tax, 2, '.', '');
                    $array_total_purchase_price_excluding_tax[] = 0;
                    $array_total_profit[] = number_format((float)$orderhour->amount_revenue_excluding_tax, 2, '.', '');
                }

                $total_price_customer_excluding_tax = number_format(array_sum($array_total_price_customer_excluding_tax), 2, '.', '');
                $total_tax_amount = number_format(array_sum($array_total_tax_amount), 2, '.', '');
                $total_price_customer_including_tax = number_format(array_sum($array_total_price_customer_including_tax), 2, '.', '');
                $total_purchase_price_excluding_tax = number_format(array_sum($array_total_purchase_price_excluding_tax), 2, '.', '');
                $total_profit = number_format(array_sum($array_total_profit), 2, '.', '');

                $order->update([
                    'total_price_customer_excluding_tax' => $total_price_customer_excluding_tax,
                    'total_tax_amount' => $total_tax_amount,
                    'total_price_customer_including_tax' => $total_price_customer_including_tax,
                    'total_purchase_price_excluding_tax' => $total_purchase_price_excluding_tax,
                    'total_profit' => $total_profit,
                    'updated_at' => $order->updated_at,
                ]);
            }
        });

        return 0;
    }
}
