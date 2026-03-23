<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'school_name' => 'SMK Kartika',
            'school_latitude' => '-5.1436',
            'school_longitude' => '119.4667',
            'school_radius' => '200', // meters
        ];

        foreach ($settings as $key => $value) {
            SchoolSetting::set($key, $value);
        }
    }
}
