<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 15; $i++) {
            Article::create([
                'title' => ucfirst($faker->words(2, true)),
                'description' => $faker->sentence(12),
                'image' => 'https://picsum.photos/seed/'.$i.'/100/200',
                'category_id' => $categories->random()->id_category,
                'price' => $faker->randomFloat(2, 5, 500),
                'quantity' => $faker->numberBetween(0, 100),
            ]);
        }
    }
}
