<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    OrderProduct,
};

class CheckIfOrderProductValuesAreCorrect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_if_order_product_values_are_correct';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for all order products if their values are correct and if not correct them.';

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
        $products = OrderProduct::get();
        foreach ($products as $product) {
            // Check if purchase price is correct
            if (round(($product->purchase_price_excluding_tax * (1 + ($product->tax_percentage / 100))), 2) != $product->purchase_price_including_tax) {
                $product->update([
                    'purchase_price_including_tax' => round(($product->purchase_price_excluding_tax * (1 + ($product->tax_percentage / 100))), 2),
                    'updated_at' => $product->updated_at,
                ]);
            }
            
            // Check if total purchase price excluding tax is correct
            if (round(($product->purchase_price_excluding_tax * $product->amount), 2) != $product->total_purchase_price_excluding_tax) {
                $product->update([
                    'total_purchase_price_excluding_tax' => round(($product->purchase_price_excluding_tax * $product->amount), 2),
                    'updated_at' => $product->updated_at,
                ]);
            }

            // Check if price customer is correct
            if (round(($product->price_customer_excluding_tax * (1 + ($product->tax_percentage / 100))), 2) != $product->price_customer_including_tax) {
                $product->update([
                    'price_customer_including_tax' => round(($product->price_customer_excluding_tax * (1 + ($product->tax_percentage / 100))), 2),
                    'updated_at' => $product->updated_at,
                ]);
            }

            // Check if revenue is correct
            if (round(($product->price_customer_excluding_tax * $product->amount), 2) != $product->revenue) {
                $product->update([
                    'revenue' => round(($product->price_customer_excluding_tax * $product->amount), 2),
                    'updated_at' => $product->updated_at,
                ]);
            }

            // Check if total price customer including tax is correct
            if (round(($product->price_customer_including_tax * $product->amount), 2) != $product->total_price_customer_including_tax) {
                $product->update([
                    'total_price_customer_including_tax' => round(($product->price_customer_including_tax * $product->amount), 2),
                    'updated_at' => $product->updated_at,
                ]);
            }

            // Check if profit is correct
            if (round(($product->revenue - $product->total_purchase_price_excluding_tax), 2) != $product->profit) {
                $product->update([
                    'profit' => round(($product->revenue - $product->total_purchase_price_excluding_tax), 2),
                    'updated_at' => $product->updated_at,
                ]);
            }
        }
        
        return 0;
    }
}
