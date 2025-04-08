<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Customer,
    OrderStatus,
    OrderProduct,
    OrderHour,
    Quote,
    Invoice,
};

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'order_status_id',
        'customer_id',
        'created_at',
        'total_price_customer_excluding_tax',
        'total_tax_amount',
        'total_price_customer_including_tax',
        'total_purchase_price_excluding_tax',
        'total_profit',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class)->orderBy('order', 'ASC');
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function order_hours()
    {
        return $this->hasMany(OrderHour::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
