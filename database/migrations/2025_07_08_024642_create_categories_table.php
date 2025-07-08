<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID dari Flutter
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('book_id');
            $table->string('name');
            $table->enum('type', ['income', 'expense']); // jenis kategori
            $table->string('icon')->nullable(); // emoji/icon kategori
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
