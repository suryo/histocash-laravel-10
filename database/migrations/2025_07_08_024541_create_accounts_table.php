<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID dari Flutter
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('book_id'); // relasi ke book
            $table->string('name');
            $table->string('icon')->nullable(); // simpan nama emoji/icon string
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
