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
        // Step 1: Add nullable slug column
        Schema::table('apartments', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Step 2: Generate slugs for existing apartments
        $apartments = \App\Models\Apartment::all();
        foreach ($apartments as $apartment) {
            $slug = \Illuminate\Support\Str::slug($apartment->name);
            $original = $slug;
            $count = 1;

            while (\App\Models\Apartment::where('slug', $slug)->where('id', '!=', $apartment->id)->exists()) {
                $slug = $original.'-'.$count++;
            }

            $apartment->slug = $slug;
            $apartment->saveQuietly();
        }

        // Step 3: Make slug non-nullable and unique
        Schema::table('apartments', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
