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
        // 1. Tambah Kolom Lokasi pada Tabel Attendances
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('latitude_in', 11, 8)->nullable()->after('photo_in');
            $table->decimal('longitude_in', 11, 8)->nullable()->after('latitude_in');
            $table->string('location_name_in')->nullable()->after('longitude_in');
            $table->string('location_status_in')->default('office')->after('location_name_in'); // office / outside

            $table->decimal('latitude_out', 11, 8)->nullable()->after('photo_out');
            $table->decimal('longitude_out', 11, 8)->nullable()->after('latitude_out');
            $table->string('location_name_out')->nullable()->after('longitude_out');
            $table->string('location_status_out')->nullable()->after('location_name_out'); // office / outside
        });

        // 2. Tambah Pengaturan Lokasi Kantor pada Tabel Attendance Settings
        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->string('office_name')->default('Kantor Stukka Digital')->after('auto_submit_countdown');
            $table->decimal('office_latitude', 11, 8)->default(-7.25747190)->after('office_name');
            $table->decimal('office_longitude', 11, 8)->default(112.75208830)->after('office_latitude');
            $table->unsignedInteger('office_radius_meters')->default(150)->after('office_longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'latitude_in',
                'longitude_in',
                'location_name_in',
                'location_status_in',
                'latitude_out',
                'longitude_out',
                'location_name_out',
                'location_status_out',
            ]);
        });

        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->dropColumn([
                'office_name',
                'office_latitude',
                'office_longitude',
                'office_radius_meters',
            ]);
        });
    }
};
