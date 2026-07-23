<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('author_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('publisher_id')->nullable()->constrained()->onDelete('set null');
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->string('language')->default('Bengali');
            $table->integer('pages')->nullable();
            $table->integer('publication_year')->nullable();
            $table->string('edition')->nullable();
            $table->string('isbn')->nullable();
            $table->decimal('file_size_mb', 8, 2)->nullable();
            $table->string('file_format')->default('PDF'); // PDF, EPUB, BOTH
            $table->string('pdf_path')->nullable();
            $table->string('epub_path')->nullable();
            $table->string('buy_link')->nullable();
            $table->string('affiliate_link')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_ratings')->default(0);
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_sponsored')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
