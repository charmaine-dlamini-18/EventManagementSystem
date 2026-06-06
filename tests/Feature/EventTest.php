<?php

use App\Models\EventCMS;
use App\Models\UserCMS;

// ── Helpers ──────────────────────────────────────────────────────────────────

function makeOrganizer(): UserCMS
{
    return UserCMS::factory()->create(['role' => 'organizer']);
}

function makeAdmin(): UserCMS
{
    return UserCMS::factory()->create(['role' => 'admin']);
}

function makeAttendee(): UserCMS
{
    return UserCMS::factory()->create(['role' => 'attendee']);
}

function eventData(array $overrides = []): array
{
    return array_merge([
        'title'       => 'Test Event',
        'description' => 'A test event description.',
        'location'    => 'Cape Town',
        'start_date'  => now()->addDays(5)->format('Y-m-d\TH:i'),
        'end_date'    => now()->addDays(5)->addHours(2)->format('Y-m-d\TH:i'),
        'status'      => 'published',
        'capacity'    => 50,
    ], $overrides);
}

// ── Index (public) ───────────────────────────────────────────────────────────

test('events index is publicly accessible', function () {
    $this->get('/events')->assertStatus(200);
});

test('events index shows published events', function () {
    EventCMS::factory()->create(['title' => 'Public Event', 'status' => 'published']);
    EventCMS::factory()->create(['title' => 'Draft Event',  'status' => 'draft']);

    $this->get('/events')->assertSee('Public Event');
});

// ── Create ───────────────────────────────────────────────────────────────────

test('organizer can access the create event page', function () {
    $this->actingAs(makeOrganizer())->get('/events/create')->assertStatus(200);
});

test('attendee cannot access the create event page', function () {
    $this->actingAs(makeAttendee())->get('/events/create')->assertStatus(403);
});

test('guest is redirected from create event page', function () {
    $this->get('/events/create')->assertRedirect('/login');
});

test('organizer can create a published event', function () {
    $organizer = makeOrganizer();

    $this->actingAs($organizer)
         ->post('/events', eventData())
         ->assertRedirect();

    $this->assertDatabaseHas('events', [
        'title'  => 'Test Event',
        'status' => 'published',
    ]);
});

test('organizer can create a draft event', function () {
    $organizer = makeOrganizer();

    $this->actingAs($organizer)
         ->post('/events', eventData(['status' => 'draft']))
         ->assertRedirect();

    $this->assertDatabaseHas('events', ['status' => 'draft']);
});

test('event creation fails without required fields', function () {
    $this->actingAs(makeOrganizer())
         ->post('/events', [])
         ->assertSessionHasErrors(['title', 'description', 'location', 'start_date', 'end_date']);
});

test('event creation fails with past start date', function () {
    $this->actingAs(makeOrganizer())
         ->post('/events', eventData(['start_date' => now()->subDay()->format('Y-m-d\TH:i')]))
         ->assertSessionHasErrors('start_date');
});

// ── Show ─────────────────────────────────────────────────────────────────────

test('anyone can view a published event', function () {
    $event = EventCMS::factory()->create(['status' => 'published']);
    $this->get("/events/{$event->id}")->assertStatus(200)->assertSee($event->title);
});

// ── Update ───────────────────────────────────────────────────────────────────

test('organizer can edit their own event', function () {
    $organizer = makeOrganizer();
    $event     = EventCMS::factory()->create(['user_id' => $organizer->id]);

    $this->actingAs($organizer)
         ->patch("/events/{$event->id}", eventData(['title' => 'Updated Title']))
         ->assertRedirect();

    $this->assertDatabaseHas('events', ['title' => 'Updated Title']);
});

test('organizer cannot edit another organizer\'s event', function () {
    $ownerEvent = EventCMS::factory()->create(['user_id' => makeOrganizer()->id]);

    $this->actingAs(makeOrganizer())
         ->patch("/events/{$ownerEvent->id}", eventData(['title' => 'Hacked Title']))
         ->assertStatus(403);

    $this->assertDatabaseMissing('events', ['title' => 'Hacked Title']);
});

test('attendee cannot update an event', function () {
    $event = EventCMS::factory()->create();

    $this->actingAs(makeAttendee())
         ->patch("/events/{$event->id}", eventData(['title' => 'Bad Edit']))
         ->assertStatus(403);
});

test('admin can update any event', function () {
    $event = EventCMS::factory()->create(['title' => 'Original']);

    $this->actingAs(makeAdmin())
         ->patch("/events/{$event->id}", eventData(['title' => 'Admin Updated']))
         ->assertRedirect();

    $this->assertDatabaseHas('events', ['title' => 'Admin Updated']);
});

// ── Delete ───────────────────────────────────────────────────────────────────

test('organizer can delete their own event', function () {
    $organizer = makeOrganizer();
    $event     = EventCMS::factory()->create(['user_id' => $organizer->id]);

    $this->actingAs($organizer)
         ->delete("/events/{$event->id}")
         ->assertRedirect();

    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});

test('organizer cannot delete another organizer\'s event', function () {
    $event = EventCMS::factory()->create(['user_id' => makeOrganizer()->id]);

    $this->actingAs(makeOrganizer())
         ->delete("/events/{$event->id}")
         ->assertStatus(403);

    $this->assertDatabaseHas('events', ['id' => $event->id]);
});

test('attendee cannot delete an event', function () {
    $event = EventCMS::factory()->create();

    $this->actingAs(makeAttendee())
         ->delete("/events/{$event->id}")
         ->assertStatus(403);
});

test('admin can delete any event', function () {
    $event = EventCMS::factory()->create();

    $this->actingAs(makeAdmin())
         ->delete("/events/{$event->id}")
         ->assertRedirect();

    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});