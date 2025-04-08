<?php

namespace App\Console\Commands;

use App\Mail\{
    PrijsopgaveContactInfoToBusinessMail,
    PrijsopgaveReminderMail,
};

use Illuminate\Console\Command;

use App\Models\{
    Prijsopgave,
    Setting,
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class SendPrijsopgaveReminderMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_prijsopgave_reminder_mails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for prijsopgaves which are unfinished and send reminder emails at set times.';

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
        $prijsopgaves = Prijsopgave::where('reminder_at', '<=', Carbon::now())->get();
        
        foreach ($prijsopgaves as $prijsopgave) {
            // Remove prijsopgave if reminder == 5
            if ($prijsopgave->reminder == 5) {
                $prijsopgave->destroy($prijsopgave->id);
                continue;
            }

            if ($prijsopgave->reminder == 1) {
                // Set reminder for day 1 at 19:30
                $next_reminder_at = Carbon::now()->startOfDay()->addDays(1)->hour(19)->minute(30)->second(0);

                // Send mail with information to business
                Mail::to(Setting::where('name','business_email')->first()->value ?? 'info@deitdokter.nl')
                    ->queue(new PrijsopgaveContactInfoToBusinessMail($prijsopgave->id, $prijsopgave->reminder));
                
            } elseif ($prijsopgave->reminder == 2) {
                // Set reminder for day 4 at 10:00
                $next_reminder_at = Carbon::now()->startOfDay()->addDays(3)->hour(10)->minute(0)->second(0);
            } elseif ($prijsopgave->reminder == 3) {
                // Set reminder for day 7 at 19:30
                $next_reminder_at = Carbon::now()->startOfDay()->addDays(3)->hour(19)->minute(30)->second(0);
            } elseif ($prijsopgave->reminder == 4) {
                // Set reminder for day 11 at 23:59
                $next_reminder_at = Carbon::now()->endOfDay()->addDays(4);
            }

            // Send reminder mail
            Mail::to($prijsopgave->email)
                ->queue(new PrijsopgaveReminderMail($prijsopgave->id, $prijsopgave->reminder));

            // Update prijsopgave
            $prijsopgave->update([
                'reminder' => ($prijsopgave->reminder + 1),
                'reminder_at' => $next_reminder_at,
            ]);
        }

        return 0;
    }
}
