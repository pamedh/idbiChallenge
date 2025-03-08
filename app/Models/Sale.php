<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales';

    protected $fillable = [
        'code',
        'customer_name',
        'customer_id',
        'customer_email',
        'seller_id',
        'total',
        'confirmed',
    ];

    protected $hidden = [
        'created_at',
    ];

    public function seller(): BelongsTo {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function salesProducts(): HasMany {
        return $this->hasMany(SaleProduct::class, 'sale_id');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'sales_products', 'sale_id', 'product_id')
                    ->withPivot('total', 'quantity');
    }
}
