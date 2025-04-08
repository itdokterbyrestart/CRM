<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\{
    Quote,
    Setting,
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteReminderMail;

class SendEmailReminderQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_email_reminder:quote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically send reminder emails after set periods of time for quotes';

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
        $amount_of_days_before_expiration_date_array = str_replace(' ', '', explode(',', Setting::where('name','email_reminder_quote_period')->first()->value));
        
        $quotes = Quote::with('quote_statuses')->get();
        $business_email = Setting::where('name','business_email')->first()->value ?? 'info@deitdokter.nl';

        foreach ($quotes as $quote) {
            if($quote->quote_statuses->first()->name == 'Wachten op klant') {
                foreach ($amount_of_days_before_expiration_date_array as $amount_of_days) {
                    if (Carbon::parse($quote->expiration_date)->subDays($amount_of_days)->startOfDay() == Carbon::now()->startOfDay()) {
                        Mail::to($quote->customer->email ?? $business_email)
                            ->queue(new QuoteReminderMail($quote->id));
                    }
                }
            }
        }
        
        return 0;
    }
}
