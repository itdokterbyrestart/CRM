<?php

namespace App\Models;

use App\Traits\uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prijsopgave extends Model
{
    use HasFactory, uuid;

    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'party_date',
        'party_date_available',
        'name',
        'email',
        'phone',
        'start_time',
        'end_time',
        'party_duration',
        'party_type',
        'location',
        'party_on_upper_floor',
        'upper_floor_elevator_available',
        'guest_amount',
        'show_type',
        'currentStep',
        'reminder',
        'reminder_at',
    ];
}
