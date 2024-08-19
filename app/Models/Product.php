<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'images',
        'category_id'
    ];

    protected $casts = [
        'images' => 'array', // Convert JSON to array
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withPivot('quantity')->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->using(OrderProduct::class)->withPivot('quantity');
    }
}
