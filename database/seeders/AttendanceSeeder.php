<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\PersonnelAttendance;
use App\Models\StudentAttendance;
use App\Models\Role;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacherRole = Role::where('name', 'teacher')->first();
        $teachers = User::where('role_id', $teacherRole->id)->get();
        $students = Student::all();

        $statuses = ['present', 'present', 'present', 'present', 'permission', 'sick', 'absent'];

        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i);

            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }

            $dateString = $date->toDateString();

            // Seed Personnel Attendance
            foreach ($teachers as $teacher) {
                PersonnelAttendance::updateOrCreate(
                    [
                        'user_id' => $teacher->id,
                        'date' => $dateString,
                    ],
                    [
                        'status' => $statuses[array_rand($statuses)],
                    ]
                );
            }

            // Seed Student Attendance
            foreach ($students as $student) {
                StudentAttendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'date' => $dateString,
                    ],
                    [
                        'status' => $statuses[array_rand($statuses)],
                    ]
                );
            }
        }
    }
}
