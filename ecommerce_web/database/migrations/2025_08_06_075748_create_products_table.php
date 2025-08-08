<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('image_url', 255)->nullable();
            $table->timestamps();
            
            // Indexes for search and filtering
            $table->index('name'); // Search by product name
            $table->index('category_id'); // Filter by category (already created by foreignId)
            $table->index('price'); // Sort/filter by price
            $table->index('stock'); // Filter by availability
            $table->index('created_at'); // Sort by newest products
            $table->index(['category_id', 'price']); // Category + price range queries
            $table->index(['category_id', 'stock']); // Available products in category
            
            // Full-text search index for name and description
            $table->fullText(['name', 'description']);
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop indexes first
            $table->dropFullText(['name', 'description']);
            $table->dropIndex(['category_id', 'stock']);
            $table->dropIndex(['category_id', 'price']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['stock']);
            $table->dropIndex(['price']);
            $table->dropIndex(['name']);
        });
        Schema::dropIfExists('products');
    }
};
