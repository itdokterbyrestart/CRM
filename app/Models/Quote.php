<?php

namespace App\Models;

use App\Traits\uuid;
use App\Models\{
    Customer,
    QuoteProduct,
    QuoteStatus,
    Order,
    Service,
    ProductGroup,
    SelectedQuoteProduct,
    Media,
};

use Spatie\MediaLibrary\{
    HasMedia,
    InteractsWithMedia,
};

use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Quote extends Model implements HasMedia, Viewable
{
    use uuid, HasFactory, InteractsWithMedia, InteractsWithViews;

    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'customer_id',
        'order_id',
        'sent_to_customer',
        'sent_at',
        'quote_text',
        'prices_exclude_tax',
        'show_product_group_images',
        'show_amount_and_total',
        'expiration_date',
        'show_packages',
        'party_date',
        'location',
        'party_type',
        'start_time',
        'end_time',
        'guest_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quote_statuses()
    {
        return $this->belongsToMany(QuoteStatus::class)
            ->withPivot('comment')
            ->withTimestamps()
            ->orderByPivot('created_at','DESC');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function quote_products()
    {
        return $this->hasMany(QuoteProduct::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)
            ->withTimestamps();
    }

    public function product_groups()
    {
        return $this->belongsToMany(ProductGroup::class)->orderBy('order', 'ASC')
            ->withTimestamps();
    }

    public function selected_quote_products()
    {
        return $this->hasMany(SelectedQuoteProduct::class);
    }

    public function clone()
    {
        // Clone item
        $clone = $this->replicate()->fill(
            [
                'title' => $this->title . ' - Kopie',
                'sent_at' => null,
                'expiration_date' => Carbon::now()->addWeeks(2),
                'order_id' => null,
            ]
        );
        $clone->push();

        // Clone package image
        foreach ($this->getMedia('packages') as $mediaItem) {
            $mediaItem->copy($clone, 'packages');
        }
        
        // Attach status
        $clone->quote_statuses()->attach(QuoteStatus::where('name', 'Nog maken')->first() ?? QuoteStatus::first());

        // Attach product groups-
        $clone->product_groups()->attach($this->product_groups()->get());

        // Clone products
        foreach ($this->quote_products as $quote_product) {
            $cloned_quote_product = $quote_product->replicate();
            $clone->quote_products()->save($cloned_quote_product);
            foreach ($quote_product->getMedia('product_images') as $mediaItem) {
                $mediaItem->copy($cloned_quote_product, 'product_images');
            }
        }

        // Return new id
        return $clone->id;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('packages')
            ->singleFile();
    }
}
