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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('amount_paid', 12, 2)->default(0)->after('net_amount');
            $table->decimal('balance_due', 12, 2)->default(0)->after('amount_paid');
            $table->timestamp('paid_at')->nullable()->after('issued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropColumn(['amount_paid', 'balance_due', 'paid_at']);
        });
    }
};
