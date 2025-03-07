<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_products';

    protected $fillable = [
        'product_id',
        'sale_id',
        'quantity',
        'total',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function sale(): BelongsTo {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
