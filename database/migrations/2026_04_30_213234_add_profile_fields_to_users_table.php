<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('email');
            $table->string('gender')->nullable()->after('nip');
            $table->string('position')->nullable()->after('gender');
        });

        // Migrate existing data from teachers
        $teachers = DB::table('teachers')->get();
        foreach ($teachers as $teacher) {
            DB::table('users')->where('id', $teacher->user_id)->update([
                'nip' => $teacher->nip,
                'gender' => $teacher->gender,
                'position' => $teacher->position ?? null,
            ]);
        }

        // Migrate existing data from staffs
        $staffs = DB::table('staffs')->get();
        foreach ($staffs as $staff) {
            DB::table('users')->where('id', $staff->user_id)->update([
                'nip' => $staff->nip,
                'gender' => $staff->gender,
            ]);
        }
        
        // Migrate gender for students
        $students = DB::table('students')->get();
        foreach ($students as $student) {
            DB::table('users')->where('id', $student->user_id)->update([
                'gender' => $student->gender,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip', 'gender', 'position']);
        });
    }
};
