<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\{
    Quote,
};

class SelectedQuoteProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'purchase_price_excluding_tax',
        'purchase_price_including_tax',
        'price_customer_excluding_tax',
        'price_customer_including_tax',
        'amount',
        'total_price_customer_excluding_tax',
        'total_price_customer_including_tax',
        'tax_percentage',
        'quote_id',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
