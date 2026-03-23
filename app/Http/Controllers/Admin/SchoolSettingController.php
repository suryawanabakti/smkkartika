<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class SchoolSettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'school_name' => SchoolSetting::get('school_name', 'SMK Kartika'),
            'school_latitude' => SchoolSetting::get('school_latitude', '-5.1436'),
            'school_longitude' => SchoolSetting::get('school_longitude', '119.4667'),
            'school_radius' => SchoolSetting::get('school_radius', '200'),
            'min_check_in_time' => SchoolSetting::get('min_check_in_time', '07:00'),
            'min_check_out_time' => SchoolSetting::get('min_check_out_time', '15:00'),
        ];

        return view('admin.settings.school', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_latitude' => 'required|numeric|between:-90,90',
            'school_longitude' => 'required|numeric|between:-180,180',
            'school_radius' => 'required|numeric|min:50|max:5000',
            'min_check_in_time' => 'required|date_format:H:i',
            'min_check_out_time' => 'required|date_format:H:i',
        ]);

        foreach ($validated as $key => $value) {
            SchoolSetting::set($key, $value);
        }

        return redirect()->route('admin.settings.school')->with('success', 'Pengaturan lokasi sekolah berhasil diperbarui.');
    }
}
