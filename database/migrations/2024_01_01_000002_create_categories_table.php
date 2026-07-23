<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // বাংলা নাম
            $table->string('name_en')->nullable(); // English name
            $table->string('slug')->unique();
            $table->string('icon')->nullable(); // Font Awesome icon class
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('color', 20)->nullable(); // hex color
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
