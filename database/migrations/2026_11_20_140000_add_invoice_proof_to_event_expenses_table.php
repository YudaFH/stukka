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
        Schema::table('event_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('event_expenses', 'invoice_proof')) {
                $table->string('invoice_proof')->nullable()->after('payment_proof');
            }
        });

        // Pindahkan file invoice lama yang sebelumnya tersimpan di kolom payment_proof
        DB::table('event_expenses')
            ->whereNull('invoice_proof')
            ->where('payment_proof', 'like', 'event_invoices/%')
            ->update([
                'invoice_proof' => DB::raw('payment_proof'),
                'payment_proof' => null,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_expenses', function (Blueprint $table) {
            if (Schema::hasColumn('event_expenses', 'invoice_proof')) {
                $table->dropColumn('invoice_proof');
            }
        });
    }
};
