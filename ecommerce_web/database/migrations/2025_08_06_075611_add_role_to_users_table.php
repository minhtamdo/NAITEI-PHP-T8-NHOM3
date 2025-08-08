<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'customer'])->default('customer')->after('password');
            
            // Indexes for search and filtering
            $table->index('name'); // Search by name
            $table->index('role'); // Filter by role
            $table->index('created_at'); // Sort by registration date
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('name');
            $table->dropIndex('role');
            $table->dropIndex('created_at');
            $table->dropColumn('role');
        });
    }
};
