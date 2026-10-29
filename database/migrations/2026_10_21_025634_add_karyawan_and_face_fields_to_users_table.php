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
            $table->string('nip')->nullable()->after('usertype');
            $table->string('position')->nullable()->after('nip');
            $table->longText('face_descriptor')->nullable()->after('position'); // JSON array of 128 floats
            $table->string('face_photo')->nullable()->after('face_descriptor'); // Path to enrolled face photo
            $table->timestamp('face_registered_at')->nullable()->after('face_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nip',
                'position',
                'face_descriptor',
                'face_photo',
                'face_registered_at',
            ]);
        });
    }
};
