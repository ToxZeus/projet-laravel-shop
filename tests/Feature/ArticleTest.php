<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\User;

it('redirects guests away from the dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

it('shows the dashboard to authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/dashboard')->assertOk();
});

it('allows an admin to create an article', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->post('/articles/create', [
        'title' => 'Article de test',
        'description' => 'Description test',
        'category_id' => $category->id_category,
        'price' => 19.99,
        'quantity' => 10,
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseHas('articles', ['title' => 'Article de test']);
});

it('blocks a regular user from creating an article', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/articles/create')->assertRedirect(route('dashboard'));
});

it('validates required fields when creating an article', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post('/articles/create', [])
        ->assertSessionHasErrors(['title', 'category_id', 'price', 'quantity']);
});

it('lets an admin soft delete an article', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();
    $article = Article::factory()->create(['category_id' => $category->id_category]);

    $this->actingAs($admin)->get('/articles/delete/' . $article->id_article)
        ->assertRedirect(route('dashboard'));

    $this->assertSoftDeleted('articles', ['id_article' => $article->id_article]);
});

it('filters articles by search term', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    Article::factory()->create(['title' => 'Casque audio', 'category_id' => $category->id_category]);
    Article::factory()->create(['title' => 'Chaussures de sport', 'category_id' => $category->id_category]);

    $response = $this->actingAs($user)->get('/dashboard?search=Casque');

    $response->assertOk();
    $response->assertSee('Casque audio');
    $response->assertDontSee('Chaussures de sport');
});
