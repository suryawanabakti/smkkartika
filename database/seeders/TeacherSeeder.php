<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacherRole = Role::where('name', 'teacher')->first();
        $teacherUsers = User::where('role_id', $teacherRole->id)->get();
        $classRooms = \App\Models\ClassRoom::all();

        foreach ($teacherUsers as $index => $user) {
            $teacher = Teacher::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => '19800101' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                ]
            );

            // Assign first 5 teachers to first 5 classes
            if ($index < $classRooms->count()) {
                $classRooms[$index]->update(['teacher_id' => $teacher->id]);
            }
        }
    }
}
