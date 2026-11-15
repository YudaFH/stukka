<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 50)->default('field_staff')->after('usertype');
            });

            // Set existing users accordingly
            DB::table('users')->where('usertype', 'admin')->update(['role' => 'full_access']);
            DB::table('users')->where('usertype', 'user')->update(['role' => 'client']);
            DB::table('users')->where('usertype', 'karyawan')->update(['role' => 'field_staff']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
