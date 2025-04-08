<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    TaxType,
    OrderProduct,
};
use Illuminate\Support\Facades\DB;

class CalculateTaxOrderProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_pre_tax_values:order_product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the pre-tax values for all existing order products';

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
        $models = OrderProduct::whereDate('created_at','>=','2022-02-02')->get();
        $table = app(OrderProduct::class)->getTable();
        foreach ($models as $model) {
            if ($model->tax_percentage == 0) {
                $purchase_price_excluding_tax = (int)$model->purchase_price / 121 * 100;
                $price_customer_excluding_tax = (int)$model->price_customer / 121 * 100;
                $revenue = $price_customer_excluding_tax * (int)$model->amount;

                DB::table($table)
                    ->where('id', $model->id)
                    ->update([
                        'purchase_price_excluding_tax' => $purchase_price_excluding_tax,
                        'purchase_price_including_tax' => (int)$model->purchase_price,
                        'price_customer' => $price_customer_excluding_tax,
                        'revenue' => $revenue,
                        'profit' => ($price_customer_excluding_tax - $purchase_price_excluding_tax),
                        'tax_percentage' => $tax_types->max('percentage'),
                    ]);
            }
        }

        return 0;
    }
}
