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
        Schema::create('event_crew', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduled_event_id')->constrained('scheduled_events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role_in_event')->nullable(); // Project Officer, Sound Engineer, Stage Crew, dll
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['scheduled_event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_crew');
    }
};
