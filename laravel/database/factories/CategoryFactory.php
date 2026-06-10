<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Электроника', 'Книги и учебники', 'Одежда и мерч', 
                'Канцтовары', 'Услуги и репетиторство', 'Спорт и отдых'
            ]),
        ];
    }
}
