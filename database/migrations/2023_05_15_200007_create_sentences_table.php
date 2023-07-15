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
        Schema::create('sentences', function (Blueprint $table) {
            $table->id();
            $table->text('sentence');
            $table->foreignId('word_type_id')->constrained();
            $table->foreignId('language_id')->constrained();
            $table->foreignId('english_word_id')->constrained('words');
            $table->foreignId('spanish_word_id')->constrained('words');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentences');
    }
};
