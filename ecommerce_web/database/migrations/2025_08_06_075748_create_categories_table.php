<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes for search
            $table->index('name'); // Search by category name
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
        Schema::dropIfExists('categories');
    }
};
