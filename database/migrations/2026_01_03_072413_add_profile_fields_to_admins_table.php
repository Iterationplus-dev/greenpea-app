<?php

use App\Enums\AdminRole;
use App\Enums\AdminStatus;
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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('address')->nullable()->after('phone');
            $table->timestamp('email_verified_at')->nullable()->after('address');

            $table->string('role')
                ->default(AdminRole::ADMIN->value)
                ->after('password');

            $table->string('type')
                ->nullable()
                ->after('role');

            $table->string('status')
                ->default(AdminStatus::ACTIVE->value)
                ->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'email_verified_at',
                'role',
                'type',
                'status',
            ]);
        });
    }
};
