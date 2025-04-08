<?php

namespace App\Console\Commands;

use App\Models\OrderProduct;
use Illuminate\Console\Command;

class CalculateRevenueOrderProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_revenue_order_products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate revenue for all order products';

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
        $orderproducts = OrderProduct::all();
        foreach ($orderproducts as $orderproduct) {
            $revenue = (int)$orderproduct->price_customer * (int)$orderproduct->amount;
            OrderProduct::where('id',$orderproduct->id)->update(['revenue' => $revenue]);
        }
        return 0;
    }
}
