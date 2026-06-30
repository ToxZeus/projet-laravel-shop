<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'user')->get();
        $articles  = Article::inStock()->get();

        if ($customers->isEmpty() || $articles->isEmpty()) {
            return;
        }

        $statuses = ['en attente', 'validée', 'expédiée', 'livrée', 'annulée'];

        foreach ($customers as $customer) {
            $orderCount = fake()->numberBetween(0, 4);

            for ($i = 0; $i < $orderCount; $i++) {
                $items = $articles->random(fake()->numberBetween(1, 4));

                $order = Order::create([
                    'user_id' => $customer->id,
                    'total'   => 0,
                    'status'  => fake()->randomElement($statuses),
                ]);

                $total = 0;

                foreach ($items as $article) {
                    $quantity = fake()->numberBetween(1, 3);
                    $price    = $article->price;

                    OrderItem::create([
                        'order_id'   => $order->id_order,
                        'article_id' => $article->id_article,
                        'quantity'   => $quantity,
                        'price'      => $price,
                    ]);

                    $total += $price * $quantity;
                }

                $order->update(['total' => $total]);
            }
        }
    }
}
