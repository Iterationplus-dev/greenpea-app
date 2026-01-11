<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table
                ->string('reference')
                ->nullable()
                ->after('id');
        });

        DB::table('bookings')
            ->whereNull('reference')
            ->orderBy('id')
            ->chunkById(100, function ($bookings) {
                foreach ($bookings as $booking) {
                    DB::table('bookings')
                        ->where('id', $booking->id)
                        ->update([
                            'reference' => 'BKG-' . strtoupper(Str::random(10)),
                        ]);
                }
            });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('reference')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {


        Schema::table('bookings', function (Blueprint $table) {
            //
             $table->dropUnique(['reference']);
            $table->dropColumn('reference');
        });
    }
};
