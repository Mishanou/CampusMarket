<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем одного фиксированного пользователя для тестирования авторизации
        $testUser = User::factory()->create([
            'name' => 'Test Student',
            'email' => 'test@example.com',
        ]);

        // Создаем еще 10 случайных пользователей
        $randomUsers = User::factory(10)->create();

        // Объединяем их в одну коллекцию, чтобы тестовый юзер тоже участвовал в рынке
        $users = collect([$testUser])->concat($randomUsers);

        // Создаем категории товаров
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

        // Имитируем отправку запросов на покупку
        for ($i = 0; $i < 15; $i++) {
            // Выбираем случайный товар
            $product = $products->random();

            // Ищем покупателя, который не является продавцом этого товара 
            $buyer = $users->where('id', '!=', $product->user_id)->random();

            // Создаем плоский заказ напрямую
            Order::factory()->create([
                'product_id' => $product->id,
                'seller_id' => $product->user_id, // Продавец — это тот, кто создал товар
                'buyer_id' => $buyer->id,
                'price_at_purchase' => $product->price, // Фиксируем цену на момент клика
                'status' => collect(['pending', 'accepted', 'declined'])->random(), // Случайный статус для разнообразия
            ]);
        }
    }
}