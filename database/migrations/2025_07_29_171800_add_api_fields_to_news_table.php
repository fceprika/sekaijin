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
        Schema::table('news', function (Blueprint $table) {
            // Add new API fields
            $table->text('summary')->nullable()->after('slug');
            $table->string('thumbnail_path')->nullable()->after('content');
            $table->enum('status', ['draft', 'published'])->default('draft')->after('author_id');
            $table->json('tags')->nullable()->after('published_at');

            // Add indexes for better performance
            $table->index(['status', 'published_at'])->after('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Remove API fields
            $table->dropColumn(['summary', 'thumbnail_path', 'status', 'tags']);

            // Remove indexes
            $table->dropIndex(['status', 'published_at']);
        });
    }
};
