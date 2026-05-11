<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $organizer = User::create([
            'name'     => 'Sarah Johnson',
            'email'    => 'sarah@example.com',
            'password' => bcrypt('password'),
            'role'     => 'organizer',
        ]);

        $organizer2 = User::create([
            'name'     => 'Michael Chen',
            'email'    => 'michael@example.com',
            'password' => bcrypt('password'),
            'role'     => 'organizer',
        ]);

        $attendeeData = [
            ['name' => 'John Smith',   'email' => 'john@example.com'],
            ['name' => 'Emma Davis',   'email' => 'emma@example.com'],
            ['name' => 'David Brown',  'email' => 'david@example.com'],
            ['name' => 'Lisa Apple',   'email' => 'lisa@example.com'],
            ['name' => 'James Wilson', 'email' => 'james@example.com'],
        ];

        $attendees = collect();
        foreach ($attendeeData as $data) {
            $attendees->push(User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => bcrypt('password'),
                'role'     => 'attendee',
            ]));
        }

        $now = Carbon::now();

        $events = [
            Event::create([
                'user_id'     => $admin->id,
                'title'       => 'Career Fair 2026',
                'description' => 'Annual career fair connecting students with top employers. Bring your resume and dress professionally.',
                'location'    => 'Bellville Campus, Main Hall',
                'start_date'  => $now->copy()->addDays(30)->setHour(9)->setMinute(0),
                'end_date'    => $now->copy()->addDays(30)->setHour(17)->setMinute(0),
                'capacity'    => 200,
                'status'      => 'published',
            ]),
            Event::create([
                'user_id'     => $organizer->id,
                'title'       => 'Health and Wellness Workshop',
                'description' => 'Learn about mental health awareness, stress management, and healthy lifestyle choices.',
                'location'    => 'District Campus, Building A',
                'start_date'  => $now->copy()->addDays(14)->setHour(10)->setMinute(0),
                'end_date'    => $now->copy()->addDays(14)->setHour(14)->setMinute(0),
                'capacity'    => 50,
                'status'      => 'published',
            ]),
            Event::create([
                'user_id'     => $organizer2->id,
                'title'       => 'Tech Talk: Laravel Best Practices',
                'description' => 'A deep dive into modern Laravel development patterns, from Policies to Observers.',
                'location'    => 'Online - Zoom',
                'start_date'  => $now->copy()->addDays(7)->setHour(14)->setMinute(0),
                'end_date'    => $now->copy()->addDays(7)->setHour(16)->setMinute(0),
                'capacity'    => 100,
                'status'      => 'published',
            ]),
            Event::create([
                'user_id'     => $organizer->id,
                'title'       => 'Draft: End of Year Gala',
                'description' => 'Annual end-of-year celebration. Details to be confirmed.',
                'location'    => 'TBA',
                'start_date'  => $now->copy()->addDays(60)->setHour(18)->setMinute(0),
                'end_date'    => $now->copy()->addDays(60)->setHour(22)->setMinute(0),
                'capacity'    => null,
                'status'      => 'draft',
            ]),
        ];

        $statuses = ['pending', 'confirmed', 'cancelled'];

        foreach ($events as $event) {
            if ($event->status !== 'published') {
                continue;
            }

            $count = rand(2, min(5, $attendees->count()));
            $selected = $attendees->random($count);

            foreach ($selected as $attendee) {
                // Avoid duplicate registrations (unique constraint)
                $exists = Registration::where('user_id', $attendee->id)
                    ->where('event_id', $event->id)
                    ->exists();

                if (! $exists) {
                    Registration::create([
                        'user_id'  => $attendee->id,
                        'event_id' => $event->id,
                        'status'   => $statuses[array_rand($statuses)],
                        'notes'    => rand(0, 1) ? 'Looking forward to this event!' : null,
                    ]);
                }
            }
        }
    }
}