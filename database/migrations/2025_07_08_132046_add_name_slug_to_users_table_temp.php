<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if column already exists
        if (Schema::hasColumn('users', 'name_slug')) {
            return; // Column already exists, skip this migration
        }

        // First, add the column without unique constraint
        Schema::table('users', function (Blueprint $table) {
            $table->string('name_slug')->nullable()->after('name');
        });

        // Populate name_slug for existing users
        $users = \App\Models\User::all();
        $usedSlugs = [];

        foreach ($users as $user) {
            $baseSlug = strtolower(preg_replace('/[^a-zA-Z0-9._-]/', '', $user->name));
            if (empty($baseSlug)) {
                $baseSlug = 'user' . $user->id;
            }

            // Ensure uniqueness
            $slug = $baseSlug;
            $counter = 1;
            while (in_array($slug, $usedSlugs)) {
                $slug = $baseSlug . $counter;
                $counter++;
            }

            $usedSlugs[] = $slug;
            $user->update(['name_slug' => $slug]);
        }

        // Now add the unique constraint with try-catch
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->string('name_slug')->nullable(false)->change();
                $table->unique('name_slug');
            });
        } catch (\Exception $e) {
            // Constraint might already exist, ignore error
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name_slug')) {
                $table->dropColumn('name_slug');
            }
        });
    }
};
