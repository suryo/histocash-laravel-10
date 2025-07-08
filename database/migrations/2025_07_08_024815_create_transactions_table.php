<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID dari Flutter
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('book_id');
            $table->uuid('account_id');
            $table->uuid('category_id')->nullable(); // bisa null untuk transfer
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->decimal('amount', 14, 2);
            $table->date('date');
            $table->string('note')->nullable();
            $table->uuid('target_account_id')->nullable(); // hanya untuk transfer
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('target_account_id')->references('id')->on('accounts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
