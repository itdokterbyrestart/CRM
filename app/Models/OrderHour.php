<?php

namespace App\Models;

use App\Models\{
    Order,
    Invoice,
};

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_customer_excluding_tax',
        'price_customer_including_tax',
        'date',
        'start_time',
        'end_time',
        'amount',
        'amount_revenue_excluding_tax',
        'amount_revenue_including_tax',
        'kilometers',
        'time_minutes',
        'description',
        'user_id',
        'order_id',
        'tax_percentage',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class)
            ->withPivot('comment')
            ->withTimestamps();
    }
}
