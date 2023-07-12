<?php

namespace App\Http\Controllers;

use App\Models\Sentence;
use App\Models\Word;
use App\Models\WordType;

class DictionaryController extends Controller
{

    public function __construct(
        protected WordController $wordController
    )
    {}

    public function addToDictionary($spanishWords)
    {
        $word = new Word();
        $sentence = new Sentence();
        $wordType = new WordType();

        foreach ($spanishWords as $spanishWord => $partsOfSpeech) {
            $this->wordController->create([
                'word' => $spanishWord,
                'language_id' => 2,
            ]);

            foreach ($partsOfSpeech as $partOfSpeech => $data) {
                $wordType->pos = $partOfSpeech;
                $wordType->pos_full = $partOfSpeech;
                $wordType->save();
            }
        }
    }
}
