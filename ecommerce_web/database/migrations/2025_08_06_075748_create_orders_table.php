<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled','return'])->default('pending');
            $table->timestamps();
            
            // Indexes for filtering and reporting
            $table->index('user_id'); // Get orders by user (already created by foreignId)
            $table->index('status'); // Filter by order status
            $table->index('created_at'); // Sort by order date
            $table->index(['user_id', 'status']); // User's orders by status
            $table->index(['status', 'created_at']); // Status + date for admin dashboard
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status']);
        });
        Schema::dropIfExists('orders');
    }
};
