<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'warehouse_id', 'quantity', 'date'
    ];

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship with Warehouse
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
