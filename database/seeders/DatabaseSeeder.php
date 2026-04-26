<?php

namespace Database\Seeders;

use App\Models\EventCMS;
use App\Models\RegistrationCMS;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $organizer = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@example.com',
            'password' => bcrypt('password'),
            'role' => 'organizer',
        ]);

        $organizer2 = User::create([
            'name' => 'Michael Chen',
            'email' => 'michael@example.com',
            'password' => bcrypt('password'),
            'role' => 'organizer',
        ]);

        $attendees = collect();
        $attendeeEmails = ['john@example.com', 'emma@example.com', 'david@example.com', 'applisa@example.com', 'james@example.com'];

        foreach ($attendeeEmails as $index => $email) {
            $attendees->push(User::create([
                'name' => explode('@', $email)[0],
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'attendee',
            ]));
        }

        $now = Carbon::now();

        $events = [
            EventCMS::create([
                'user_id' => $admin->id,
                'title' => 'Career Fair 2026',
                'description' => 'Annual career fair connecting students with top employers. Bring your resume and dress professionally.',
                'location' => 'Bellville Campus, Main Hall',
                'start_date' => $now->copy()->addDays(30)->setHour(9)->setMinute(0),
                'end_date' => $now->copy()->addDays(30)->setHour(17)->setMinute(0),
                'capacity' => 200,
                'status' => 'published',
            ]),
            EventCMS::create([
                'user_id' => $organizer->id,
                'title' => 'Health and Wellness Workshop',
                'description' => 'Learn about mental health awareness, stress management, and healthy lifestyle choices.',
                'location' => 'District Campus, Building A',
                'start_date' => $now->copy()->addDays(14)->setHour(10)->setMinute(0),
                'end_date' => $now->copy()->addDays(14)->setHour(14)->setMinute(0),
                'capacity' => 50,
                'status' => 'published',
            ]),
        ];

        $registrationStatuses = ['pending', 'confirmed', 'cancelled'];

        foreach ($events as $event) {
            if ($event->status !== 'published') {
                continue;
            }

            $registrationCount = rand(0, min(5, $attendees->count()));
            $selectedAttendees = $attendees->random($registrationCount);

            foreach ($selectedAttendees as $attendee) {
                $status = $registrationStatuses[array_rand($registrationStatuses)];

                RegistrationCMS::create([
                    'user_id' => $attendee->id,
                    'event_id' => $event->id,
                    'status' => $status,
                    'notes' => rand(0, 1) ? 'Looking forward to this event!' : null,
                ]);
            }
        }
    }
}