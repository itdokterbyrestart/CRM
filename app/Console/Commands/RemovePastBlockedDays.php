<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    Setting,
};
use Illuminate\Support\Carbon;


class RemovePastBlockedDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove_past_blocked_days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for days in the past in the blocked days list and remove if applicable';

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
        $setting = Setting::where('name','blocked_dates')->first();
        
        $blocked_dates_array = array_map(function ($value) {return Carbon::parse($value)->startOfDay();}, explode(',', str_replace(' ', '', $setting->value ?? '')));

        $new_blocked_dates_array = [];
        $current_date = Carbon::now()->startOfDay();
        
        foreach ($blocked_dates_array as $blocked_date) {
            if ($blocked_date >= $current_date) {
                $new_blocked_dates_array[] = $blocked_date->format('Y-m-d');
            }
        }

        $setting->update([
            'value' => implode(',', $new_blocked_dates_array)
        ]);
        
        return 0;
    }
}
