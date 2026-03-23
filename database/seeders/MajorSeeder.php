<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majors = [
            [
                'name' => 'Rekayasa Perangkat Lunak',
                'code' => 'RPL',
            ],
            [
                'name' => 'Teknik Komputer dan Jaringan',
                'code' => 'TKJ',
            ],
            [
                'name' => 'Multimedia',
                'code' => 'MM',
            ],
            [
                'name' => 'Teknik Bisnis Sepeda Motor',
                'code' => 'TBSM',
            ],
            [
                'name' => 'Akuntansi',
                'code' => 'AK',
            ],
        ];

        foreach ($majors as $major) {
            Major::updateOrCreate(['code' => $major['code']], $major);
        }
    }
}
