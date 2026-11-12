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
        Schema::table('event_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('event_payments', 'reference_no')) {
                $table->string('reference_no')->nullable()->after('payment_method');
            }
            $table->string('payment_name')->default('Pembayaran Klien')->change();
        });

        Schema::table('event_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('event_expenses', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_date');
            }
            $table->string('expense_name')->default('Pengeluaran Event')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_payments', function (Blueprint $table) {
            if (Schema::hasColumn('event_payments', 'reference_no')) {
                $table->dropColumn('reference_no');
            }
        });

        Schema::table('event_expenses', function (Blueprint $table) {
            if (Schema::hasColumn('event_expenses', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
