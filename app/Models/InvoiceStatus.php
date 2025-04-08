<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Invoice,
};

class InvoiceStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contextual_class',
        'order',
    ];

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class)
            ->withTimestamps()
            ->withPivot('comment')
            ->orderByPivot('created_at','DESC');
    }
}
