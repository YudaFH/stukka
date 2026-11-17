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
        Schema::table('attendances', function (Blueprint $table) {
            $table->text('work_summary')->nullable()->after('notes');
            $table->string('work_file')->nullable()->after('work_summary');
            $table->string('work_file_name')->nullable()->after('work_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['work_summary', 'work_file', 'work_file_name']);
        });
    }
};
