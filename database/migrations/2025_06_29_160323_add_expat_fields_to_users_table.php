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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('name');
            $table->string('last_name')->after('first_name');
            $table->date('birth_date')->after('last_name');
            $table->string('phone')->nullable()->after('birth_date');
            $table->string('country_residence')->nullable()->after('phone');
            $table->string('city_residence')->nullable()->after('country_residence');
            $table->text('bio')->nullable()->after('city_residence');
            $table->boolean('is_verified')->default(false)->after('bio');
            $table->timestamp('last_login')->nullable()->after('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'birth_date',
                'phone',
                'country_residence',
                'city_residence',
                'bio',
                'is_verified',
                'last_login',
            ]);
        });
    }
};
