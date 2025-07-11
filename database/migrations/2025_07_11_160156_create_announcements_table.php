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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['vente', 'location', 'colocation', 'service']);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->string('country');
            $table->string('city');
            $table->string('address')->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['pending', 'active', 'refused'])->default('pending');
            $table->date('expiration_date')->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'country', 'city']);
            $table->index(['type', 'status']);
            $table->index('expiration_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
