<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price', 
        'status'
    ];

    // Заказ принадлежит пользователю
    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // В заказе несколько вещей
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
