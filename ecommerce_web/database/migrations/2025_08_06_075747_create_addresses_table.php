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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            // Liên kết với bảng users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('full_name');
            $table->string('phone_number');
            $table->string('details')->nullable(); // Căn hộ, tòa nhà (tuỳ chọn)
            $table->string('ward');
            $table->string('district');
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Vietnam');

            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
