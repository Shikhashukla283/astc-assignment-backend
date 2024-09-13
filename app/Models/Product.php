<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'quantity', 'supplier_id'
    ];

    // Relationship with Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship with Warehouses (Many-to-Many)
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class)->withPivot('quantity')->withTimestamps();
    }

    // Relationship with StockHistory
    public function stockHistory()
    {
        return $this->hasMany(StockHistory::class);
    }
}
