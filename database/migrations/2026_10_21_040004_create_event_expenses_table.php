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
        Schema::create('event_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduled_event_id')->constrained('scheduled_events')->cascadeOnDelete();
            $table->string('expense_name'); // Sewa Sound System, Panggung & Tenda, Konsumsi Kru, dll
            $table->enum('category', [
                'sound',        // Sound System & Multimedia
                'stage',        // Panggung, Tenda & Lighting
                'catering',     // Konsumsi & Catering
                'logistics',    // Transportasi & Logistik
                'crew',         // Honor Kru Freelance / Talent
                'venue',        // Sewa Venue & Perizinan
                'other'         // Lain-lain
            ])->default('other');
            $table->string('vendor_name')->nullable(); // Nama Vendor / Pihak Penerima
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid'); // paid = sudah dibayar, unpaid = belum dibayar / hutang
            $table->date('due_date')->nullable(); // Jatuh tempo pembayaran
            $table->date('payment_date')->nullable(); // Tanggal pelunasan
            $table->string('payment_proof')->nullable(); // Bukti pembayaran / nota
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_expenses');
    }
};
