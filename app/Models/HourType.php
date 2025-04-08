<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_customer_excluding_tax',
        'price_customer_including_tax',
        'tax_percentage',
    ];
}
