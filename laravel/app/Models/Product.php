<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title', 
        'description', 
        'price', 
        'image_url', 
        'stock'
    ];

    // Товар пренадлежит категории
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Товар принадлежит продавцу
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
