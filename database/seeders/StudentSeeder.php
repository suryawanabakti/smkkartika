<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Role;
use App\Models\ClassRoom;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRole = Role::where('name', 'student')->first();
        $studentUsers = User::where('role_id', $studentRole->id)->get();
        $classes = ClassRoom::all();

        foreach ($studentUsers as $index => $user) {
            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'class_id' => $classes->random()->id,
                    'nis' => '2024' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'address' => 'Jl. Pendidikan No. ' . ($index + 1),
                ]
            );
        }
    }
}
