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
            // Vérifier et ajouter les colonnes manquantes
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('birth_date');
            }
            if (!Schema::hasColumn('users', 'country_residence')) {
                $table->string('country_residence')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'destination_country')) {
                $table->string('destination_country')->nullable()->after('country_residence');
            }
            if (!Schema::hasColumn('users', 'is_visible_on_map')) {
                $table->boolean('is_visible_on_map')->default(false)->after('destination_country');
            }
            if (!Schema::hasColumn('users', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('is_visible_on_map');
            }
            if (!Schema::hasColumn('users', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('users', 'city_detected')) {
                $table->string('city_detected')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('users', 'country_id')) {
                $table->foreignId('country_id')->nullable()->after('city_detected');
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'city_residence')) {
                $table->string('city_residence')->nullable()->after('country_id');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('city_residence');
            }
            if (!Schema::hasColumn('users', 'youtube_username')) {
                $table->string('youtube_username')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('users', 'youtube_username_alt')) {
                $table->string('youtube_username_alt')->nullable()->after('youtube_username');
            }
            if (!Schema::hasColumn('users', 'instagram_username')) {
                $table->string('instagram_username')->nullable()->after('youtube_username_alt');
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
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('telegram_username');
            }
            if (!Schema::hasColumn('users', 'last_login')) {
                $table->timestamp('last_login')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('free')->after('last_login');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées (dans l'ordre inverse)
            $columnsToRemove = [
                'role', 'last_login', 'is_verified', 'telegram_username', 'facebook_username',
                'twitter_username', 'linkedin_username', 'tiktok_username', 'instagram_username',
                'youtube_username_alt', 'youtube_username', 'bio', 'city_residence', 'country_id',
                'city_detected', 'longitude', 'latitude', 'is_visible_on_map', 'destination_country',
                'country_residence', 'phone', 'birth_date', 'last_name', 'first_name'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};