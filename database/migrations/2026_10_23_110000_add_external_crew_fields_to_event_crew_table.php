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
        if (!Schema::hasColumn('event_crew', 'crew_type')) {
            Schema::table('event_crew', function (Blueprint $table) {
                $table->string('crew_type')->default('internal')->after('user_id');
                $table->string('external_name')->nullable()->after('crew_type');
                $table->string('external_phone')->nullable()->after('external_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('event_crew', 'crew_type')) {
            Schema::table('event_crew', function (Blueprint $table) {
                $table->dropColumn(['crew_type', 'external_name', 'external_phone']);
            });
        }
    }
};
