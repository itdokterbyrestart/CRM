<?php

namespace App\Console\Commands;

use App\Models\OrderProduct;
use Illuminate\Console\Command;

class CalculateProfitOrderProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_profit_order_products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate profit for all order products';

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
            $profit = (int)$orderproduct->revenue - ((int)$orderproduct->purchase_price_excluding_tax * (int)$orderproduct->amount);
            OrderProduct::where('id',$orderproduct->id)->update(['profit' => $profit]);
        }
        return 0;
    }
}
