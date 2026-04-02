<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Staff;
use App\Models\Role;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffRole = Role::where('name', 'staff')->first();
        if (!$staffRole) return;

        $staffUsers = User::where('role_id', $staffRole->id)->get();

        foreach ($staffUsers as $index => $user) {
            Staff::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => 'STAFF' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                ]
            );
        }
    }
}
