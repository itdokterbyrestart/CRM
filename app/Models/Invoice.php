<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Models\{
    Order,
    OrderProduct,
    OrderHour,
};

use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Invoice extends Model implements HasMedia, Viewable
{
    use Uuid, HasFactory, InteractsWithMedia, InteractsWithViews;

    protected $keyType = 'Uuid';
    public $incrementing = false;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'expiration_date',
        'order_id',
        'sent_to_customer',
        'sent_at',
        'total_price_customer_excluding_tax',
        'total_tax_amount',
        'total_price_customer_including_tax',
        'payment_id',
        'custom_text',
        'extra_invoice_data',
        'calculation_method_excluding_tax',
    ];

    public function invoice_statuses()
    {
        return $this->belongsToMany(InvoiceStatus::class)
            ->withTimestamps()
            ->withPivot('comment')
            ->orderByPivot('created_at','DESC');
    }

    public function order_products()
    {
        return $this->belongsToMany(OrderProduct::class)
            ->withPivot('comment','order')
            ->withTimestamps();
    }

    public function order_hours()
    {
        return $this->belongsToMany(OrderHour::class)
            ->withPivot('comment')
            ->withTimestamps();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('invoice')
            ->acceptsMimeTypes(['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf'])
            ->useDisk('invoices')
            ->singleFile();
    }
}
