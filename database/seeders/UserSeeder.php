<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@smkkartika.sch.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        // Create Teachers Users
        for ($i = 1; $i <= 10; $i++) {
            User::updateOrCreate(
                ['email' => "teacher{$i}@smkkartika.sch.id"],
                [
                    'name' => "Teacher Number {$i}",
                    'password' => Hash::make('password'),
                    'role_id' => $teacherRole->id,
                ]
            );
        }

        // Create Students Users
        for ($i = 1; $i <= 50; $i++) {
            User::updateOrCreate(
                ['email' => "student{$i}@smkkartika.sch.id"],
                [
                    'name' => "Student Number {$i}",
                    'password' => Hash::make('password'),
                    'role_id' => $studentRole->id,
                ]
            );
        }

        $staffRole = Role::where('name', 'staff')->first();
        // Create Staff Users
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "staff{$i}@smkkartika.sch.id"],
                [
                    'name' => "Staff Number {$i}",
                    'password' => Hash::make('password'),
                    'role_id' => $staffRole->id,
                ]
            );
        }
    }
}
