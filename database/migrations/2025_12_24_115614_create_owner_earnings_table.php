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
        Schema::create('owner_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();

            $table->decimal('gross_amount', 12, 2);
            $table->decimal('platform_fee', 12, 2);
            $table->decimal('net_amount', 12, 2);

            $table->date('earned_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_earnings');
    }
};
