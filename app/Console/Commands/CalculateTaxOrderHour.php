<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    TaxType,
    OrderHour,
};
use Illuminate\Support\Facades\DB;

class CalculateTaxOrderHour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_pre_tax_values:order_hour';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the pre-tax values for all existing order hours';

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
        $models = OrderHour::whereDate('created_at','>=','2022-02-02')->get();
        $table = app(OrderHour::class)->getTable();
        foreach ($models as $model) {
            if ($model->tax_percentage == 0) {
                DB::table($table)
                    ->where('id', $model->id)
                    ->update([
                        'tax_percentage' => $tax_types->max('percentage'),
                    ]);
            }
        }
        
        return 0;
    }
}
