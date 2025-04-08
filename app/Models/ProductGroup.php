<?php

namespace App\Models;

use App\Models\{
    Product,
    Quote,
};

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'description',
        'description_before_products',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order','ASC');
    }

    public function quotes()
    {
        return $this->belongsToMany(Quote::class)
            ->withTimestamps();
    }
}
