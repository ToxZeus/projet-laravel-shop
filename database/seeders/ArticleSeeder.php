<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        if (Category::count() === 0) {
            return;
        }

        // 50 articles en stock variable, dont quelques-uns en rupture
        Article::factory()->count(45)->create();
        Article::factory()->outOfStock()->count(5)->create();
    }
}
