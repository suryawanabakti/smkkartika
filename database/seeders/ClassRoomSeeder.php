<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassRoom;
use App\Models\Major;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majors = Major::all();

        foreach ($majors as $major) {
            $grades = ['X', 'XI', 'XII'];
            $groups = ['1', '2'];

            foreach ($grades as $grade) {
                foreach ($groups as $group) {
                    ClassRoom::updateOrCreate([
                        'major_id' => $major->id,
                        'name' => $grade . ' ' . $major->code . ' ' . $group,
                    ]);
                }
            }
        }
    }
}
