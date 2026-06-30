<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Électronique', 'Vêtements', 'Maison', 'Sport', 'Livres', 'Jouets',
                'Beauté', 'Alimentation', 'Jardin', 'Auto-moto',
            ]),
        ];
    }
}
