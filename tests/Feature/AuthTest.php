<?php

use App\Models\UserCMS;

// ── Registration ────────────────────────────────────────────────────────────

test('registration page is accessible', function () {
    $this->get('/register')->assertStatus(200);
});

test('attendee can register with valid data', function () {
    $this->post('/register', [
        'name'                  => 'Jane Doe',
        'email'                 => 'jane@example.com',
        'role'                  => 'attendee',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect('/dashboard');

    $this->assertDatabaseHas('users', [
        'email' => 'jane@example.com',
        'role'  => 'attendee',
    ]);
});

test('organizer can register with valid data', function () {
    $this->post('/register', [
        'name'                  => 'John Org',
        'email'                 => 'john@example.com',
        'role'                  => 'organizer',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect('/dashboard');

    $this->assertDatabaseHas('users', ['role' => 'organizer']);
});

test('registration fails without required fields', function () {
    $this->post('/register', [])->assertSessionHasErrors(['name', 'email', 'password', 'role']);
});

test('registration fails with duplicate email', function () {
    UserCMS::factory()->create(['email' => 'taken@example.com']);

    $this->post('/register', [
        'name'                  => 'Other User',
        'email'                 => 'taken@example.com',
        'role'                  => 'attendee',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors('email');
});

test('registration fails with mismatched passwords', function () {
    $this->post('/register', [
        'name'                  => 'Jane',
        'email'                 => 'jane@example.com',
        'role'                  => 'attendee',
        'password'              => 'password',
        'password_confirmation' => 'different',
    ])->assertSessionHasErrors('password');
});

// ── Login ───────────────────────────────────────────────────────────────────

test('login page is accessible', function () {
    $this->get('/login')->assertStatus(200);
});

test('user can login with correct credentials', function () {
    $user = UserCMS::factory()->create([
        'email'    => 'user@example.com',
        'password' => bcrypt('password'),
        'role'     => 'attendee',
    ]);

    $this->post('/login', [
        'email'    => 'user@example.com',
        'password' => 'password',
    ])->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);
});

test('login fails with wrong password', function () {
    UserCMS::factory()->create([
        'email'    => 'user@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->post('/login', [
        'email'    => 'user@example.com',
        'password' => 'wrongpassword',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('login fails with non-existent email', function () {
    $this->post('/login', [
        'email'    => 'nobody@example.com',
        'password' => 'password',
    ])->assertSessionHasErrors('email');
});

// ── Logout ──────────────────────────────────────────────────────────────────

test('authenticated user can logout', function () {
    $user = UserCMS::factory()->create();

    $this->actingAs($user)
         ->post('/logout')
         ->assertRedirect('/');

    $this->assertGuest();
});

// ── Guest guards ─────────────────────────────────────────────────────────────

test('guests are redirected to login from dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users are redirected away from login page', function () {
    $user = UserCMS::factory()->create();
    $this->actingAs($user)->get('/login')->assertRedirect('/dashboard');
});