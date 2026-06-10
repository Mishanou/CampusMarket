<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем одного фиксированного пользователя для удобного тестирования авторизации
        User::factory()->create([
            'name' => 'Test Student',
            'email' => 'test@example.com',
        ]);

        // Создаем еще 10 случайных пользователей
        $users = User::factory(10)->create();

        // Создаем категории товаров (сгенерирует до 6 уникальных категорий из пула)
        $categories = Category::factory(6)->create();

        // Создаем товары и распределяем их между категориями и случайными продавцами
        $products = collect();
        foreach ($categories as $category) {
            // Для каждой категории генерируем от 3 до 5 товаров
            $products->push(...Product::factory(rand(3, 5))->create([
                'category_id' => $category->id,
                'user_id' => $users->random()->id, // Случайный студент становится продавцом товара
            ]));
        }

        // Имитируем оформление заказов покупателями
        for ($i = 0; $i < 15; $i++) {
            $buyer = $users->random();

            // Создаем заказ для покупателя
            $order = Order::factory()->create([
                'user_id' => $buyer->id,
            ]);

            // Выбираем от 1 до 3 случайных товаров для этого заказа
            $randomProducts = $products->random(rand(1, 3));

            foreach ($randomProducts as $product) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 2),
                    'price' => $product->price, // Фиксируем историческую цену товара на момент покупки
                ]);
            }
        }
    }
}