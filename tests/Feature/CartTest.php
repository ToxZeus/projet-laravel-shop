<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\User;

it('lets a user add an article to the cart', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $article = Article::factory()->create([
        'category_id' => $category->id_category,
        'quantity' => 10,
    ]);

    $response = $this->actingAs($user)->post('/cart/add', [
        'article_id' => $article->id_article,
        'quantity' => 2,
    ]);

    $response->assertRedirect(route('cart.index'));
    $this->assertDatabaseHas('cart', [
        'user_id' => $user->id,
        'article_id' => $article->id_article,
        'quantity' => 2,
    ]);
});

it('refuses to add more items than available stock', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $article = Article::factory()->create([
        'category_id' => $category->id_category,
        'quantity' => 3,
    ]);

    $this->actingAs($user)->post('/cart/add', [
        'article_id' => $article->id_article,
        'quantity' => 5,
    ])->assertRedirect(route('cart.index'));

    $this->assertDatabaseMissing('cart', [
        'user_id' => $user->id,
        'article_id' => $article->id_article,
    ]);
});

it('lets a user remove an item from the cart', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $article = Article::factory()->create(['category_id' => $category->id_category, 'quantity' => 5]);

    $this->actingAs($user)->post('/cart/add', [
        'article_id' => $article->id_article,
        'quantity' => 1,
    ]);

    $cartId = \App\Models\Cart::where('user_id', $user->id)->first()->id_cart;

    $this->actingAs($user)->post('/cart/remove/' . $cartId)
        ->assertRedirect(route('cart.index'));

    $this->assertDatabaseMissing('cart', ['id_cart' => $cartId]);
});
