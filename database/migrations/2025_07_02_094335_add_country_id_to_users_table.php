<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new country_id column
            $table->foreignId('country_id')->nullable()->after('country_residence');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
        });

        // Populate country_id based on existing country_residence values
        $this->populateCountryIds();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');
        });
    }

    /**
     * Populate country_id values based on existing country_residence names.
     */
    private function populateCountryIds(): void
    {
        // Get all countries for mapping
        $countries = DB::table('countries')->get()->keyBy('name_fr');

        // Update users with matching country_id
        foreach ($countries as $country) {
            DB::table('users')
                ->where('country_residence', $country->name_fr)
                ->update(['country_id' => $country->id]);
        }
    }
};
