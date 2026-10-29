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
        Schema::create('event_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduled_event_id')->constrained('scheduled_events')->cascadeOnDelete();
            $table->string('payment_name'); // DP 30%, Termin 2, Pelunasan, dll
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('payment_method')->default('Transfer Bank'); // Transfer Bank, Cash, dll
            $table->enum('status', ['paid', 'pending'])->default('paid'); // paid = sudah diterima, pending = invoice terbit belum bayar
            $table->string('receipt_proof')->nullable(); // Bukti transfer / kwitansi
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_payments');
    }
};
