<?php

namespace App\Models;

use App\Models\{
    ProductGroup,
    Service,
    Media,
};

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Spatie\MediaLibrary\{
    HasMedia,
    InteractsWithMedia,
};

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'purchase_price_excluding_tax',
        'purchase_price_including_tax',
        'price_customer_excluding_tax',
        'price_customer_including_tax',
        'profit',
        'description',
        'link',
        'tax_percentage',
        'use_discount_price',
        'discount_price_customer_including_tax',
        'discount_price_customer_excluding_tax',
    ];

    public function product_groups()
    {
        return $this->belongsToMany(ProductGroup::class)->orderBy('order', 'ASC')
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order','ASC');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images');
    }

    public function clone()
    {
        // Clone item
        $clone = $this->replicate()->fill(
            [
                'name' => $this->name . ' - Kopie',
            ]
        );
        $clone->push();

        // Attach product groups-
        $clone->product_groups()->attach($this->product_groups()->get());

        // Clone products
        foreach ($this->getMedia('product_images') as $mediaItem) {
            $mediaItem->copy($clone, 'product_images');
        }

        // Return new id
        return $clone->id;
    }
}
