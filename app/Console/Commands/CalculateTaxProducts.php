<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    TaxType,
    Product,
};
use Illuminate\Support\Facades\DB;

class CalculateTaxProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_pre_tax_values:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the pre-tax values for all existing products';

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
        // Tax Types
        $tax_types = TaxType::all();

        // Products
        $models = Product::all();
        $table = app(Product::class)->getTable();
        foreach ($models as $model) {
            if ($model->tax_percentage == 0) {
                $purchase_price_excluding_tax = (int)$model->purchase_price / 121 * 100;
                $price_customer_excluding_tax = (int)$model->price_customer_excluding_tax / 121 * 100;
                $price_customer_including_tax = (int)$model->price_customer_excluding_tax;

                DB::table($table)
                    ->where('id', $model->id)
                    ->update([
                        'purchase_price_excluding_tax' => $purchase_price_excluding_tax,
                        'purchase_price_including_tax' => (int)$model->purchase_price,
                        'price_customer_excluding_tax' => $price_customer_excluding_tax,
                        'price_customer_including_tax' => $price_customer_including_tax,
                        'profit' => ($price_customer_excluding_tax - $purchase_price_excluding_tax),
                        'tax_percentage' => $tax_types->max('percentage'),
                    ]);
            }
        }


        return 0;
    }
}
