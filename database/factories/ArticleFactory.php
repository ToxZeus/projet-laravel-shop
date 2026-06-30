<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    private static int $imageIndex = 0;

    public function definition(): array
    {
        self::$imageIndex++;

        return [
            'title'       => ucfirst($this->faker->words(fake()->numberBetween(2, 4), true)),
            'description' => $this->faker->paragraph(3),
            'image'       => 'https://picsum.photos/seed/' . self::$imageIndex . '/400/300',
            'category_id' => Category::inRandomOrder()->value('id_category') ?? 1,
            'price'       => $this->faker->randomFloat(2, 2, 499),
            'quantity'    => $this->faker->numberBetween(0, 150),
        ];
    }

    public function inStock(): static
    {
        return $this->state(fn () => [
            'quantity' => fake()->numberBetween(5, 150),
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => [
            'quantity' => 0,
        ]);
    }
}
