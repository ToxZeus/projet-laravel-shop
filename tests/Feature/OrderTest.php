<?php

use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Models\Article;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('lets a user place an order from their cart and dispatches OrderPlaced', function () {
    Event::fake([OrderPlaced::class]);

    $user = User::factory()->create();
    $category = Category::factory()->create();
    $article = Article::factory()->create(['category_id' => $category->id_category, 'quantity' => 10, 'price' => 25]);

    Cart::create(['user_id' => $user->id, 'article_id' => $article->id_article, 'quantity' => 2]);

    $response = $this->actingAs($user)->post('/orders');

    $response->assertRedirect();
    $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total' => 50]);
    $this->assertDatabaseCount('cart', 0);
    expect($article->fresh()->quantity)->toBe(8);

    Event::assertDispatched(OrderPlaced::class);
});

it('blocks ordering with an empty cart', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/orders')->assertRedirect(route('cart.index'));
    $this->assertDatabaseCount('orders', 0);
});

it('blocks a regular user from updating an order status', function () {
    $user = User::factory()->create();
    $order = Order::create(['user_id' => $user->id, 'total' => 10, 'status' => 'en attente']);

    $this->actingAs($user)->patch('/orders/' . $order->id_order . '/status', ['status' => 'validée'])
        ->assertRedirect(route('dashboard'));

    expect($order->fresh()->status)->toBe('en attente');
});

it('allows an admin to update an order status and dispatches OrderStatusChanged', function () {
    Event::fake([OrderStatusChanged::class]);

    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create();
    $order = Order::create(['user_id' => $customer->id, 'total' => 10, 'status' => 'en attente']);

    $this->actingAs($admin)->patch('/orders/' . $order->id_order . '/status', ['status' => 'validée'])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe('validée');
    Event::assertDispatched(OrderStatusChanged::class);
});

it('prevents a user from viewing another user\'s order', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::create(['user_id' => $owner->id, 'total' => 10, 'status' => 'en attente']);

    $this->actingAs($other)->get('/orders/' . $order->id_order)
        ->assertRedirect(route('orders.index'));
});
