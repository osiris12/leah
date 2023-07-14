<?php

namespace App\Http\Controllers;


use App\Models\Definition;
use App\Models\Sentence;
use App\Models\Translation;
use App\Models\Word;
use App\Models\WordType;
use Illuminate\Support\Facades\DB;

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

    public function getFromDictionary($word)
    {
        return DB::table('words', 'w')
            ->select('w.id', 'w.word', 'w.language_id', 'definitions.definition', 'word_types.pos_full', 'languages.name')
            ->leftJoin('definitions', 'w.id', '=', 'definitions.word_id')
            ->leftJoin('word_types', 'definitions.word_type_id', '=', 'word_types.id')
            ->leftJoin('languages', 'w.language_id', '=', 'languages.id')
            ->where('w.word', '=', $word)
            ->get();


    }

    /*
     * SELECT
        w.id,w.word, w.language_id,
        df.definition,
        wt.pos_full,
        la.name
        FROM words w
        LEFT JOIN definitions df ON w.id = df.word_id
        LEFT JOIN word_types wt ON df.word_type_id = wt.id
        LEFT JOIN languages la ON w.language_id = la.id
        WHERE w.word = 'reir';
     */
}















































