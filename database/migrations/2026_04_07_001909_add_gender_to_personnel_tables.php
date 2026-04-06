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
        Schema::table('students', function (Blueprint $table) {
            $table->enum('gender', ['L', 'P'])->default('L')->after('id');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->enum('gender', ['L', 'P'])->default('L')->after('id');
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->enum('gender', ['L', 'P'])->default('L')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('gender');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('gender');
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};
