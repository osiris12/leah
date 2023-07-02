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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_word_id');
            $table->foreignId('translated_word_id');
            $table->timestamps();
        });

        $translations = [
            [
                'source_word_id' => 1,
                'translated_word_id' => 2
            ],
            [
                'source_word_id' => 1,
                'translated_word_id' => 3
            ],
        ];

        foreach($translations as $translation) {
            $data = new \App\Models\Translation();
            foreach($translation as $key => $val) {
                $data->$key = $val;
            }
            $data->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
