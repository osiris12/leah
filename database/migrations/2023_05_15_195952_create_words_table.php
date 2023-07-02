<?php

use App\Models\Word;
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
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->string('word');
            $table->foreignId('language_id')->constrained();
            $table->timestamps();
        });

        $words = [
            [
                'word' => 'Ruidoso',
                'language_id' => 2
            ],
            [
                'word' => 'Noisy',
                'language_id' => 1
            ],
            [
                'word' => 'Loud',
                'language_id' => 2
            ],
        ];

        foreach($words as $word) {
            $data = new Word();
            foreach($word as $key => $val) {
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
        Schema::dropIfExists('words');
    }
};
