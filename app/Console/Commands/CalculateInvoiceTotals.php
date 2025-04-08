<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    Invoice,
};

class CalculateInvoiceTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_invoice_totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates totals for all products and hours attached to invoices and update the invoice totals';

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
        $invoices = Invoice::with('order_products', 'order_hours')->get();
        foreach ($invoices as $invoice) {
            $array_total_price_customer_excluding_tax = [];
            $array_total_tax_amount = [];
            $array_total_price_customer_including_tax = [];
    
            foreach ($invoice->order_products as $product) {
                $array_total_price_customer_excluding_tax[] = number_format((float)$product->revenue, 2, '.', '');
                $array_total_tax_amount[] = number_format(((float)$product->total_price_customer_including_tax - (float)$product->revenue), 2, '.', '');
                $array_total_price_customer_including_tax[] = number_format((float)$product->total_price_customer_including_tax, 2, '.', '');
            }

            foreach ($invoice->order_hours as $hour) {
                $array_total_price_customer_excluding_tax[] = number_format((float)$hour->amount_revenue_excluding_tax, 2, '.', '');
                $array_total_tax_amount[] = number_format((float)$hour->amount_revenue_including_tax - (float)$hour->amount_revenue_excluding_tax, 2, '.', '');
                $array_total_price_customer_including_tax[] = number_format((float)$hour->amount_revenue_including_tax, 2, '.', '');
            }
    
            $total_price_customer_excluding_tax = number_format(array_sum($array_total_price_customer_excluding_tax), 2, '.', '');
            $total_tax_amount = number_format(array_sum($array_total_tax_amount), 2, '.', '');
            $total_price_customer_including_tax = number_format(array_sum($array_total_price_customer_including_tax), 2, '.', '');

            $invoice->update([
                'total_price_customer_excluding_tax' => $total_price_customer_excluding_tax,
                'total_tax_amount' => $total_tax_amount,
                'total_price_customer_including_tax' => $total_price_customer_including_tax,
                'updated_at' => $invoice->updated_at,
            ]);
        }
        
        return 0;
    }
}
