<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'payment_status',
        'order_status',
        'user_id',
        'location_lat',
        'location_lng',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->using(OrderProduct::class)->withPivot('quantity');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
