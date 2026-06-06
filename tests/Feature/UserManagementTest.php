<?php

use App\Models\UserCMS;

// ── Access control ───────────────────────────────────────────────────────────

test('admin can access user management index', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);
    $this->actingAs($admin)->get('/users')->assertStatus(200);
});

test('organizer cannot access user management', function () {
    $organizer = UserCMS::factory()->create(['role' => 'organizer']);
    $this->actingAs($organizer)->get('/users')->assertStatus(403);
});

test('attendee cannot access user management', function () {
    $attendee = UserCMS::factory()->create(['role' => 'attendee']);
    $this->actingAs($attendee)->get('/users')->assertStatus(403);
});

test('guest is redirected from user management', function () {
    $this->get('/users')->assertRedirect('/login');
});

// ── Create user ──────────────────────────────────────────────────────────────

test('admin can access the create user page', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);
    $this->actingAs($admin)->get('/users/create')->assertStatus(200);
});

test('admin can create a new user', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post('/users', [
        'name'     => 'New User',
        'email'    => 'newuser@example.com',
        'password' => 'password',
        'role'     => 'attendee',
    ])->assertRedirect();

    $this->assertDatabaseHas('users', [
        'email' => 'newuser@example.com',
        'role'  => 'attendee',
    ]);
});

test('admin can create an organizer account', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post('/users', [
        'name'     => 'New Organizer',
        'email'    => 'org@example.com',
        'password' => 'password',
        'role'     => 'organizer',
    ])->assertRedirect();

    $this->assertDatabaseHas('users', ['role' => 'organizer']);
});

test('admin can create another admin account', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post('/users', [
        'name'     => 'New Admin',
        'email'    => 'admin2@example.com',
        'password' => 'password',
        'role'     => 'admin',
    ])->assertRedirect();

    $this->assertDatabaseHas('users', ['email' => 'admin2@example.com', 'role' => 'admin']);
});

test('user creation fails without required fields', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
         ->post('/users', [])
         ->assertSessionHasErrors(['name', 'email', 'password', 'role']);
});

test('user creation fails with duplicate email', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);
    UserCMS::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($admin)->post('/users', [
        'name'     => 'Dupe',
        'email'    => 'taken@example.com',
        'password' => 'password',
        'role'     => 'attendee',
    ])->assertSessionHasErrors('email');
});

// ── Show user ─────────────────────────────────────────────────────────────────

test('admin can view a user profile', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);
    $user  = UserCMS::factory()->create();

    $this->actingAs($admin)
         ->get("/users/{$user->id}")
         ->assertStatus(200)
         ->assertSee($user->name);
});

// ── Update user ───────────────────────────────────────────────────────────────

test('admin can update a user', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);
    $user  = UserCMS::factory()->create(['name' => 'Old Name']);

    $this->actingAs($admin)->patch("/users/{$user->id}", [
        'name'  => 'New Name',
        'email' => $user->email,
        'role'  => $user->role,
    ])->assertRedirect();

    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
});

test('admin can change a user\'s role', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);
    $user  = UserCMS::factory()->create(['role' => 'attendee']);

    $this->actingAs($admin)->patch("/users/{$user->id}", [
        'name'  => $user->name,
        'email' => $user->email,
        'role'  => 'organizer',
    ])->assertRedirect();

    $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'organizer']);
});

test('non-admin cannot update a user', function () {
    $organizer = UserCMS::factory()->create(['role' => 'organizer']);
    $user      = UserCMS::factory()->create(['name' => 'Target']);

    $this->actingAs($organizer)
         ->patch("/users/{$user->id}", ['name' => 'Hacked', 'email' => $user->email, 'role' => $user->role])
         ->assertStatus(403);
});

// ── Delete user ───────────────────────────────────────────────────────────────

test('admin can delete a user', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);
    $user  = UserCMS::factory()->create();

    $this->actingAs($admin)
         ->delete("/users/{$user->id}")
         ->assertRedirect();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('admin cannot delete their own account via user management', function () {
    $admin = UserCMS::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
         ->delete("/users/{$admin->id}")
         ->assertStatus(403);

    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('non-admin cannot delete a user', function () {
    $attendee = UserCMS::factory()->create(['role' => 'attendee']);
    $user     = UserCMS::factory()->create();

    $this->actingAs($attendee)
         ->delete("/users/{$user->id}")
         ->assertStatus(403);

    $this->assertDatabaseHas('users', ['id' => $user->id]);
});