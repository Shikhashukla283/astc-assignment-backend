<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'location'
    ];

    // Relationship with Products (Many-to-Many)
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    }
    public function productsDetail()
    {
        return $this->belongsToMany(Product::class, 'product_warehouse')->withPivot('quantity')->withTimestamps();
    }
}
