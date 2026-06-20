<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'seller_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'buyer_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'price_at_purchase' => $this->faker->numberBetween(100, 5000),
            'status' => 'pending',
        ];
    }
}