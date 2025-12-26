<?php

use App\Enums\Cities;
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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            // Ownership
            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Property info
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Location
            $table->enum('city', array_column(Cities::cases(), 'value'));
            $table->string('address');

            // Metadata
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
