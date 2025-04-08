<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\{
    Quote,
    QuoteStatus,
};
use Carbon\Carbon;

class CheckForExpiredQuotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired_quotes:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired quotes and change status to expired. Only works for quotes that have the status waiting for customer.';

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
        $quotes = Quote::with('quote_statuses')->get();
        $expired = QuoteStatus::where('name','Verlopen')->first();

        foreach ($quotes as $quote) {
            if($quote->quote_statuses->first()->name == 'Wachten op klant') {
                if (Carbon::parse($quote->expiration_date)->startOfDay() < Carbon::now()->startOfDay()) {
                    $quote->quote_statuses()->attach($expired->id);
                }
            }
        }
        
        return 0;
    }
}
