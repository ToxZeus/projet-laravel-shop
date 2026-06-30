<?php

use App\Models\User;

it('blocks a regular user from the admin user list', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin/users')
        ->assertRedirect(route('dashboard'));
});

it('allows an admin to view the user list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->count(3)->create();

    $this->actingAs($admin)->get('/admin/users')->assertOk();
});

it('blocks a regular user from category management', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/categories')
        ->assertRedirect(route('dashboard'));
});

it('allows an admin to manage categories', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->get('/categories')->assertOk();
});

it('prevents an admin from changing their own role', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->patch('/admin/users/' . $admin->id . '/role', [
        'role' => 'user',
    ])->assertRedirect();

    expect($admin->fresh()->role)->toBe('admin');
});
