<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator with full access'],
            ['name' => 'organizer', 'description' => 'Can create and manage events'],
            ['name' => 'attendee', 'description' => 'Can register for events'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}