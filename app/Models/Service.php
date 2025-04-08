<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\{
    Customer,
    Quote,
    Product,
};

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'product_id',
    ];

    public function customers()
    {
        return $this->belongsToMany(Customer::class)
            ->withPivot('month','description')
            ->withTimestamps();
    }

    public function quotes()
    {
        return $this->belongsToMany(Quote::class)
            ->withTimestamps();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
