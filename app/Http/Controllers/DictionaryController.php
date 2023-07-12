<?php

namespace App\Http\Controllers;


use App\Models\Definition;
use App\Models\Sentence;
use App\Models\Translation;
use App\Models\Word;
use App\Models\WordType;

class DictionaryController extends Controller
{

    public function addToDictionary($spanishWords)
    {
        foreach ($spanishWords as $spanishWord => $partsOfSpeech) {
            $currentSpanishWord = Word::firstOrCreate( // Find or create Word if it does not exist.
                ['word' => $spanishWord],
                ['language_id' => 2],
            );
            $currentSpanishWordId = $currentSpanishWord->id;

            foreach ($partsOfSpeech['parts_of_speech'] as $partOfSpeech => $definitionsAndEnglishWords) {
                $currentPos = WordType::firstOrCreate( // Find or create Word Type if it does not exist.
                    ['pos' => $partOfSpeech],
                    ['pos_full' => $partOfSpeech],
                );
                $currentPosId = $currentPos->id;

                foreach ($definitionsAndEnglishWords['spanish_definitions'] as $definition) {
                    $definition = preg_replace('/\(|\)/', '', $definition); // Remove parentheses from definition.
                    Definition::firstOrCreate(
                        ['word_id' => $currentSpanishWordId, 'word_type_id' => $currentPosId, 'definition' => $definition],
                    );
                }

                foreach ($definitionsAndEnglishWords['english_words'] as $englishWord => $sentences) {
                    $currentEnglishWord = Word::firstOrCreate( // Find or create Word if it does not exist.
                        ['word' => $englishWord],
                        ['language_id' => 1],
                    );
                    $currentEnglishWordId = $currentEnglishWord->id;

                    Translation::firstOrCreate(
                        ['source_word_id' => $currentSpanishWordId, 'translated_word_id' => $currentEnglishWordId],
                    );

                    foreach ($sentences['sentences'] as $language => $sentence) {
                        $languageCodes = ['english' => 1 ,'spanish' => 2];
                        Sentence::firstOrCreate(
                            [
                                'english_word_id' => $currentEnglishWordId,
                                'spanish_word_id' => $currentSpanishWordId,
                                'language_id'     => $languageCodes[$language],
                                'sentence' => $sentence,
                            ]
                        );
                    }
                }
            }
        }
        return response(['message' => 'Records added successfully'], 201);
    }
}
