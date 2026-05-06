<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\TeacherSchedule;

class TeacherScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = Teacher::all();
        $classes = ClassRoom::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $subjects = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS', 'Informatika', 'Olahraga', 'Agama'];

        if ($teachers->isEmpty() || $classes->isEmpty()) {
            return;
        }

        foreach ($teachers as $teacher) {
            // Assign 2-3 subjects per day for each teacher
            foreach ($days as $day) {
                $numSchedules = rand(1, 2);
                $usedPeriods = [];

                for ($i = 0; $i < $numSchedules; $i++) {
                    $periodStart = rand(1, 8);
                    $periodEnd = $periodStart + rand(1, 2);

                    // Basic overlap check
                    $overlap = false;
                    for ($p = $periodStart; $p <= $periodEnd; $p++) {
                        if (in_array($p, $usedPeriods)) {
                            $overlap = true;
                            break;
                        }
                    }

                    if (!$overlap && $periodEnd <= 11) {
                        TeacherSchedule::create([
                            'teacher_id' => $teacher->id,
                            'day' => $day,
                            'subject' => $subjects[array_rand($subjects)],
                            'class_id' => $classes->random()->id,
                            'period_start' => $periodStart,
                            'period_end' => $periodEnd,
                        ]);

                        for ($p = $periodStart; $p <= $periodEnd; $p++) {
                            $usedPeriods[] = $p;
                        }
                    }
                }
            }
        }
    }
}
