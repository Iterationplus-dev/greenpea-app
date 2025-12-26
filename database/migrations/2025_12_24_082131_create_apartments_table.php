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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();

            // Property relationship
            $table->foreignId('property_id')
                ->constrained()
                ->cascadeOnDelete();

            // Apartment info
            $table->string('name');
            $table->string('unit_number')->nullable();
            $table->integer('floor')->nullable();
            $table->text('description');

            // Pricing
            $table->decimal('monthly_price', 12, 2);

            // Specs
            $table->integer('bedrooms')->default(1);
            $table->integer('bathrooms')->default(1);

            // Status
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            // Prevent duplicate units in the same property
            $table->unique(['property_id', 'unit_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
