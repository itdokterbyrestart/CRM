<?php

namespace App\Livewire\Auth\Profile;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Models\{
    OrderHour,
    User,
};
use Carbon\Carbon;
use Auth;


class Show extends Component
{
    public $orderhours;
    public $profile, $user_id;
    public $hour_start_date, $hour_end_date;

    protected $listeners = [
        'refreshPage' => '$refresh',
    ];

    public function mount()
    {
        $this->profile = Auth::user();
        $this->hour_start_date = Carbon::now()->startOfWeek()->subWeeks(1);
        $this->hour_end_date = Carbon::now()->startOfWeek()->subSecond(1);
    }

    public function updatedStartDate()
    {
        $this->hour_start_date = Carbon::parse($this->start_date)->startOfDay();
    }

    public function updatedEndDate()
    {
        $this->hour_end_date = Carbon::parse($this->end_date)->endOfDay();
    }
    
    public function render()
    {
        $this->orderhours = OrderHour::query()
            ->whereBetween('date', [$this->hour_start_date,$this->hour_end_date])
            ->where('user_id', $this->profile->id)
            ->with('order.customer')
            ->get();
        
        return view('auth.profile.livewire.show');
    }
}
