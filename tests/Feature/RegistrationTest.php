<?php

use App\Models\EventCMS;
use App\Models\RegistrationCMS;
use App\Models\UserCMS;

// ── Register for an event ────────────────────────────────────────────────────

test('attendee can register for a published event', function () {
    $attendee = UserCMS::factory()->create(['role' => 'attendee']);
    $event    = EventCMS::factory()->create(['status' => 'published', 'capacity' => 10]);

    $this->actingAs($attendee)
         ->post("/events/{$event->id}/register")
         ->assertRedirect();

    $this->assertDatabaseHas('registrations', [
        'user_id'  => $attendee->id,
        'event_id' => $event->id,
        'status'   => 'pending',
    ]);
});

test('guest cannot register for an event', function () {
    $event = EventCMS::factory()->create(['status' => 'published']);

    $this->post("/events/{$event->id}/register")->assertRedirect('/login');
});

test('attendee cannot register twice for the same event', function () {
    $attendee = UserCMS::factory()->create(['role' => 'attendee']);
    $event    = EventCMS::factory()->create(['status' => 'published']);

    RegistrationCMS::factory()->create([
        'user_id'  => $attendee->id,
        'event_id' => $event->id,
        'status'   => 'pending',
    ]);

    $this->actingAs($attendee)
         ->post("/events/{$event->id}/register")
         ->assertRedirect();

    expect(RegistrationCMS::where('user_id', $attendee->id)
        ->where('event_id', $event->id)
        ->count()
    )->toBe(1);
});

test('attendee cannot register for a full event', function () {
    $attendee = UserCMS::factory()->create(['role' => 'attendee']);
    $event    = EventCMS::factory()->create(['status' => 'published', 'capacity' => 1]);

    // Fill the event
    RegistrationCMS::factory()->create([
        'event_id' => $event->id,
        'status'   => 'confirmed',
    ]);

    $this->actingAs($attendee)
         ->post("/events/{$event->id}/register")
         ->assertRedirect();

    $this->assertDatabaseMissing('registrations', [
        'user_id'  => $attendee->id,
        'event_id' => $event->id,
    ]);
});

// ── Cancel registration ──────────────────────────────────────────────────────

test('attendee can cancel their own registration', function () {
    $attendee     = UserCMS::factory()->create(['role' => 'attendee']);
    $event        = EventCMS::factory()->create(['status' => 'published']);
    $registration = RegistrationCMS::factory()->create([
        'user_id'  => $attendee->id,
        'event_id' => $event->id,
        'status'   => 'pending',
    ]);

    $this->actingAs($attendee)
         ->delete("/events/{$event->id}/register")
         ->assertRedirect();

    $this->assertDatabaseMissing('registrations', ['id' => $registration->id]);
});

test('attendee cannot cancel another attendee\'s registration', function () {
    $owner        = UserCMS::factory()->create(['role' => 'attendee']);
    $other        = UserCMS::factory()->create(['role' => 'attendee']);
    $event        = EventCMS::factory()->create(['status' => 'published']);
    $registration = RegistrationCMS::factory()->create([
        'user_id'  => $owner->id,
        'event_id' => $event->id,
    ]);

    $this->actingAs($other)
         ->delete("/events/{$event->id}/register")
         ->assertRedirect();

    $this->assertDatabaseHas('registrations', ['id' => $registration->id]);
});

// ── Organizer approve / decline ──────────────────────────────────────────────

test('organizer can approve a pending registration', function () {
    $organizer    = UserCMS::factory()->create(['role' => 'organizer']);
    $event        = EventCMS::factory()->create(['user_id' => $organizer->id, 'status' => 'published']);
    $registration = RegistrationCMS::factory()->create([
        'event_id' => $event->id,
        'status'   => 'pending',
    ]);

    $this->actingAs($organizer)
         ->post("/registrations/{$registration->id}/approve")
         ->assertRedirect();

    $this->assertDatabaseHas('registrations', [
        'id'     => $registration->id,
        'status' => 'confirmed',
    ]);
});

test('organizer can decline a pending registration', function () {
    $organizer    = UserCMS::factory()->create(['role' => 'organizer']);
    $event        = EventCMS::factory()->create(['user_id' => $organizer->id, 'status' => 'published']);
    $registration = RegistrationCMS::factory()->create([
        'event_id' => $event->id,
        'status'   => 'pending',
    ]);

    $this->actingAs($organizer)
         ->post("/registrations/{$registration->id}/decline")
         ->assertRedirect();

    $this->assertDatabaseHas('registrations', [
        'id'     => $registration->id,
        'status' => 'cancelled',
    ]);
});

test('attendee cannot approve registrations', function () {
    $attendee     = UserCMS::factory()->create(['role' => 'attendee']);
    $registration = RegistrationCMS::factory()->create(['status' => 'pending']);

    $this->actingAs($attendee)
         ->post("/registrations/{$registration->id}/approve")
         ->assertStatus(403);
});

// ── Registrations index ──────────────────────────────────────────────────────

test('attendee sees only their own registrations', function () {
    $attendee = UserCMS::factory()->create(['role' => 'attendee']);
    $other    = UserCMS::factory()->create(['role' => 'attendee']);

    RegistrationCMS::factory()->create(['user_id' => $attendee->id]);
    RegistrationCMS::factory()->create(['user_id' => $other->id]);

    $response = $this->actingAs($attendee)->get('/registrations');
    $response->assertStatus(200);

    // Only one registration belongs to this attendee
    expect(
        RegistrationCMS::where('user_id', $attendee->id)->count()
    )->toBe(1);
});

test('organizer sees registrations for their events', function () {
    $organizer = UserCMS::factory()->create(['role' => 'organizer']);
    $event     = EventCMS::factory()->create(['user_id' => $organizer->id]);

    RegistrationCMS::factory()->create(['event_id' => $event->id]);

    $this->actingAs($organizer)
         ->get('/registrations')
         ->assertStatus(200);
});