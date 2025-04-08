<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Order,
    Service,
};

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'street',
        'number',
        'postal_code',
        'place_name',
        'discount',
        'comment',
        'phone',
        'phone_2',
        'phone_3',
        'email_2',
        'email_3',
        'company',
        'generated',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('month','description')
            ->withTimestamps();
    }
}
