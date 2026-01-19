<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.$booking->update(['receipt_sent_at' => now()]);
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->boolean('is_fully_paid')->default(false)->after('status');
            $table->timestamp('paid_at')->nullable()->after('is_fully_paid');
            $table->timestamp('receipt_sent_at')->nullable()->after('paid_at');

            $table->index('is_fully_paid');
            $table->index('receipt_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->dropColumn([
                'is_fully_paid',
                'paid_at',
                'receipt_sent_at'
            ]);
        });
    }
};
