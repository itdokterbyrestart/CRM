<?php

namespace App\Console\Commands;

use App\Models\{
    Service,
    Product,
    QuoteProduct,
    OrderProduct,
    SelectedQuoteProduct,
};
use Illuminate\Console\Command;

class CalculatePurchasePriceIncludingTaxProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_purchase_price_including_tax_products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the purchase price including taxes for all products';

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
        // Products
        $products = Product::all();
        foreach($products as $product) {
            $purchase_price_including_tax = $product->purchase_price_excluding_tax * (1 + ($product->tax_percentage / 100));
            $purchase_price_including_tax = number_format($purchase_price_including_tax, 2, '.', '');
            $product->update([
                'purchase_price_including_tax' => $purchase_price_including_tax,
                'updated_at' => $product->updated_at,
            ]);
        }

        $products = null;
        $product = null;
        $purchase_price_including_tax = null;

        // Quote products
        $quote_products = QuoteProduct::all();
        foreach ($quote_products as $product) {
            $purchase_price_including_tax = $product->purchase_price_excluding_tax * (1 + ($product->tax_percentage / 100));
            $purchase_price_including_tax = number_format($purchase_price_including_tax, 2, '.', '');
            $product->update([
                'purchase_price_including_tax' => $purchase_price_including_tax,
                'updated_at' => $product->updated_at,
            ]);
        }

        $quote_products = null;
        $product = null;
        $purchase_price_including_tax = null;

        // Order producuts
        $order_products = OrderProduct::all();
        foreach ($order_products as $product) {
            $purchase_price_including_tax = $product->purchase_price_excluding_tax * (1 + ($product->tax_percentage / 100));
            $purchase_price_including_tax = number_format($purchase_price_including_tax, 2, '.', '');
            $product->update([
                'purchase_price_including_tax' => $purchase_price_including_tax,
                'total_purchase_price_excluding_tax' => number_format(($product->purchase_price_excluding_tax * $product->amount), 2, '.', ''),
                'updated_at' => $product->updated_at,
            ]);
        }

        $order_products = null;
        $product = null;
        $purchase_price_including_tax = null;

        // Selected Quote Products
        $selected_quote_products = SelectedQuoteProduct::all();
        foreach ($selected_quote_products as $product) {
            $purchase_price_including_tax = $product->purchase_price_excluding_tax * (1 + ($product->tax_percentage / 100));
            $purchase_price_including_tax = number_format($purchase_price_including_tax, 2, '.', '');
            $product->update([
                'purchase_price_including_tax' => $purchase_price_including_tax,
                'updated_at' => $product->updated_at,
            ]);
        }

        $selected_quote_products = null;
        $product = null;
        $purchase_price_including_tax = null;

        return 0;
    }
}
