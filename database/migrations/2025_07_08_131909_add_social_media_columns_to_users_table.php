<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add social media columns only if they don't exist
            if (!Schema::hasColumn('users', 'instagram_username')) {
                $table->string('instagram_username')->nullable()->after('youtube_username');
            }
            if (!Schema::hasColumn('users', 'tiktok_username')) {
                $table->string('tiktok_username')->nullable()->after('instagram_username');
            }
            if (!Schema::hasColumn('users', 'linkedin_username')) {
                $table->string('linkedin_username')->nullable()->after('tiktok_username');
            }
            if (!Schema::hasColumn('users', 'twitter_username')) {
                $table->string('twitter_username')->nullable()->after('linkedin_username');
            }
            if (!Schema::hasColumn('users', 'facebook_username')) {
                $table->string('facebook_username')->nullable()->after('twitter_username');
            }
            if (!Schema::hasColumn('users', 'telegram_username')) {
                $table->string('telegram_username')->nullable()->after('facebook_username');
            }
            
            // Add avatar column only if it doesn't exist
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('email');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'instagram_username',
                'tiktok_username', 
                'linkedin_username',
                'twitter_username',
                'facebook_username',
                'telegram_username',
                'avatar'
            ]);
        });
    }
};