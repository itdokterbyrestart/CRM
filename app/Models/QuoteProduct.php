<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\{
    Quote,
    Media
};

use Spatie\MediaLibrary\{
    HasMedia,
    InteractsWithMedia,
};

class QuoteProduct extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'purchase_price_excluding_tax',
        'purchase_price_including_tax',
        'price_customer_excluding_tax',
        'price_customer_including_tax',
        'amount',
        'total_price_customer_excluding_tax',
        'total_price_customer_including_tax',
        'tax_percentage',
        'description',
        'quote_id',
        'show_product_images',
        'order',
        'highlight_text',
        'discount_price_customer_excluding_tax',
        'discount_price_customer_including_tax',
        'total_discount_price_customer_excluding_tax',
        'total_discount_price_customer_including_tax',
        'use_discount_prices',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images');
    }
}
