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
        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->time('check_in_start_time')->default('06:00:00')->after('id');
            $table->time('check_in_end_time')->default('11:00:00')->after('late_tolerance_minutes');
            $table->time('check_out_start_time')->default('16:00:00')->after('check_in_end_time');
            $table->time('check_out_end_time')->default('23:59:00')->after('work_end_time');
            $table->text('work_days')->nullable()->after('check_out_end_time'); // JSON: ["monday", "tuesday", ...]
            $table->integer('min_face_match_score')->default(60)->after('work_days'); // Persentase kecocokan minimal
            $table->integer('auto_submit_countdown')->default(2)->after('min_face_match_score'); // Hitung mundur otomatis (detik)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->dropColumn([
                'check_in_start_time',
                'check_in_end_time',
                'check_out_start_time',
                'check_out_end_time',
                'work_days',
                'min_face_match_score',
                'auto_submit_countdown',
            ]);
        });
    }
};
