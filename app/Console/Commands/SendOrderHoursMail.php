<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    User,
    Setting,
};
use App\Mail\OrderHoursMail;
use Illuminate\Support\Facades\Mail;

class SendOrderHoursMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OrderHourMail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email with the hours made in the last week';

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
        $business_email = Setting::where('name','business_email')->first()->value ?? 'info@deitdokter.nl';

        $users = User::where('mail_report', true)->get();
        foreach ($users as $user) {
            Mail::to($business_email)
                ->queue(new OrderHoursMail($user->id));
        }
        return 0;
    }
}
