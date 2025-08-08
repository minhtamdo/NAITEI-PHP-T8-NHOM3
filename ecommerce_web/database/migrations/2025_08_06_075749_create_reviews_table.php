<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->check('rating >= 1 AND rating <= 5');
            $table->text('content')->nullable();
            $table->string('image_url', 255)->nullable();
            $table->timestamps();
            
            // Indexes for review queries and analysis
            $table->index('rating'); // Filter by rating
            $table->index('created_at'); // Sort by review date
            $table->index(['product_id', 'rating']); // Product reviews by rating
            
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop constraints and indexes first
            $table->dropIndex(['product_id', 'rating']);
            $table->dropIndex('created_at');
            $table->dropIndex('rating');
        });
        Schema::dropIfExists('reviews');
    }
};
