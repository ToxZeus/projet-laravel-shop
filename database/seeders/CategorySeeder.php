<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Électronique', 'Vêtements', 'Maison', 'Sport', 'Livres', 'Jouets'];

        foreach ($categories as $name) {
            // evite doublons
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
