<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Quote,
};

class QuoteStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contextual_class',
        'order',
    ];

    public function quotes()
    {
        return $this->belongsToMany(Quote::class)
            ->withPivot('comment')
            ->withTimestamps()
            ->orderByPivot('created_at','DESC');
    }
}
