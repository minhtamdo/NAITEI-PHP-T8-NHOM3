<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->unsigned();
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop indexes first
        });
        Schema::dropIfExists('order_items');
    }
};
