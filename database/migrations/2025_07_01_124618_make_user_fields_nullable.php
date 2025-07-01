<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->date('birth_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<< HEAD
=======
        // Add data cleanup before making fields non-nullable to prevent errors
        DB::table('users')->whereNull('first_name')->update(['first_name' => '']);
        DB::table('users')->whereNull('last_name')->update(['last_name' => '']);
        DB::table('users')->whereNull('birth_date')->update(['birth_date' => '1900-01-01']);
        
>>>>>>> c8e2084e62a0cfe411cf933a42abbd7bb5f7073c
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
            $table->date('birth_date')->nullable(false)->change();
        });
    }
};
