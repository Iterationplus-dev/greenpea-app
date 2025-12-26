<?php

use App\Enums\GateWayStatus;
use App\Enums\PaymentMethod;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('gateway')->default('paystack');
            // $table->string('payment_method')->default('paystack');
            $table->enum('payment_method', array_column(PaymentMethod::cases(), 'value'))->default(PaymentMethod::PAYSTACK->value);
            $table->string('reference')->unique();
            $table->enum('status', array_column(GateWayStatus::cases(), 'value'))->default(GateWayStatus::PENDING->value);
            $table->boolean('is_installment')->default(false);
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
