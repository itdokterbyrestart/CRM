<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\{
    Invoice,
    InvoiceStatus,
};
use Carbon\Carbon;
use Illuminate\Support\Str;


class CheckForExpiredInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired_invoices:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired invoices and change status to expired. Only works for invoices that have the status waiting for payment.';

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
        $invoices = Invoice::with('invoice_statuses')->get();
        $expired = InvoiceStatus::where('name','Verlopen')->first();

        foreach ($invoices as $invoice) {
            $invoice_status = $invoice->invoice_statuses->first();
            if($invoice_status->name == 'Wachten op betaling') {
                if(Carbon::parse($invoice->expiration_date) < Carbon::now()->startOfDay()) {
                    $invoice->invoice_statuses()->attach($expired->id);
                }
            }

            if(Str::contains($invoice_status->name, 'Herinnering')) {
                if(Carbon::parse($invoice_status->pivot->created_at)->addDays(7) < Carbon::now()->startOfDay()) {
                    $invoice->invoice_statuses()->attach($expired->id);
                }
            }
        }
        
        return 0;
    }
}
