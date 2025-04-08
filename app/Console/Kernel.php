<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\{
    Setting,
};

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $settings = Setting::whereIn('name',['scheduled_send_order_hour_mail','send_email_reminder_quote','schedule_create_new_orders_from_services','schedule_change_order_status_before_show','schedule_change_order_status_after_show','send_show_invitation_reminder_mail_period','send_apk_reminder_mail_period','enable_prijsopgave_module'])->get();

        if ($settings->where('name','scheduled_send_order_hour_mail')->first()->value == 1) {
            $schedule->command('OrderHourMail:send')->weeklyOn(2,'7:00');
        }

        $schedule->command('expired_invoices:check')->dailyAt('4:00');
        $schedule->command('expired_quotes:check')->dailyAt('4:00');

        if ($settings->where('name','send_email_reminder_quote')->first()->value == 1) {
            $schedule->command('send_email_reminder:quote')->dailyAt('10:00');
        }

        if ($settings->where('name','schedule_create_new_orders_from_services')->first()->value == 1) {
            $schedule->command('create_new_orders_from_services')->monthlyOn(1,'10:00');
        }

        if ($settings->where('name','schedule_change_order_status_before_show')->first()->value == 1) {
            $schedule->command('change_order_status_before_show_and_send_scheduling_mail')->weeklyOn(1,'10:00');
        }

        if ($settings->where('name','schedule_change_order_status_after_show')->first()->value == 1) {
            $schedule->command('change_order_status_after_show')->weeklyOn(1,'10:00');
        }

        if ($settings->where('name','send_show_invitation_reminder_mail_period')->first()->value != 0) {
            $schedule->command('send_show_invitation_reminder_mail')->dailyAt('10:00');
        }

        if ($settings->where('name','send_apk_reminder_mail_period')->first()->value != 0) {
            $schedule->command('send_apk_reminder_mail')->dailyAt('10:00');
        }

        if ($settings->where('name','enable_prijsopgave_module')->first()->value == 1) {
            $schedule->command('send_prijsopgave_reminder_mails')->everyFiveMinutes();
            $schedule->command('remove_past_blocked_days')->dailyAt('4:00');
        }

        $schedule->command('queue:work --stop-when-empty');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
