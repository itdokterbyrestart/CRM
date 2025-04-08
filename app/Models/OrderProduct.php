<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\{
    Order,
};

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'supplier',
        'order_number',
        'user_id',
        'purchase_price_excluding_tax',
        'purchase_price_including_tax',
        'price_customer_excluding_tax',
        'price_customer_including_tax',
        'amount',
        'revenue',
        'profit',
        'total_price_customer_including_tax',
        'description',
        'order_id',
        'tax_percentage',
        'total_purchase_price_excluding_tax',
        'created_at',
        'order',
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
