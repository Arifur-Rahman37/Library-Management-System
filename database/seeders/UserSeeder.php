<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@library.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+8801234567890',
            'address' => 'Dhaka, Bangladesh',
            'membership_date' => Carbon::now(),
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        // Librarians
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Librarian {$i}",
                'email' => "librarian{$i}@library.com",
                'password' => Hash::make('password'),
                'role' => 'librarian',
                'phone' => '+8801' . rand(10000000, 99999999),
                'address' => 'Library Staff Area',
                'membership_date' => Carbon::now(),
                'is_active' => true,
                'email_verified_at' => Carbon::now(),
            ]);
        }

        // 20 Members
        $memberNames = [
            'John Doe', 'Jane Smith', 'Michael Brown', 'Sarah Johnson',
            'William Taylor', 'Emma Wilson', 'James Anderson', 'Olivia Martinez',
            'Robert Garcia', 'Sophia Lee', 'David Clark', 'Isabella Rodriguez',
            'Richard Lewis', 'Mia Walker', 'Joseph Hall', 'Charlotte Allen',
            'Daniel Young', 'Amanda King', 'Matthew Wright', 'Jessica Scott'
        ];

        foreach ($memberNames as $index => $name) {
            User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'phone' => '+8801' . rand(10000000, 99999999),
                'address' => 'House ' . ($index + 1) . ', Road ' . ($index + 1) . ', Dhaka',
                'membership_date' => Carbon::now()->subDays(rand(1, 365)),
                'is_active' => true,
                'email_verified_at' => Carbon::now(),
            ]);
        }
    }
}