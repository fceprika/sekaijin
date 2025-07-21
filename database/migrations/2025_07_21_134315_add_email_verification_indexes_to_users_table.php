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
            // Add index on email_verified_at for performance
            $table->index('email_verified_at', 'users_email_verified_at_idx');
            
            // Add composite index for email verification queries
            $table->index(['email', 'email_verified_at'], 'users_email_verification_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes in reverse order
            $table->dropIndex('users_email_verification_idx');
            $table->dropIndex('users_email_verified_at_idx');
        });
    }
};
