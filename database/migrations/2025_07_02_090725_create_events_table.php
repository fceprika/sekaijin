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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('full_description')->nullable();
            $table->string('category')->default('networking');
            $table->string('image_url')->nullable();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('online_link')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
