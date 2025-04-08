<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    TaxType,
    HourType,
};
use Illuminate\Support\Facades\DB;

class CalculateTaxHourType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_pre_tax_values:hour_type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the pre-tax values for all existing hour types';

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
        $models = HourType::all();
        $table = app(HourType::class)->getTable();
        foreach ($models as $model) {
            if ($model->tax_percentage == 0) {
                DB::table($table)
                    ->where('id', $model->id)
                    ->update([
                        'tax_percentage' => $tax_types->max('percentage'),
                        'price_customer_including_tax' => ($model->price_customer_excluding_tax * 1.21),
                    ]);
            }
        }
        
        return 0;
    }
}
