<?php

namespace App\Http\Controllers;


use App\Models\Definition;
use App\Models\Sentence;
use App\Models\Translation;
use App\Models\Word;
use App\Models\WordType;
use Illuminate\Database\Query\JoinClause;
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

            foreach ($partsOfSpeech['parts_of_speech'] as $partOfSpeech => $spanishDefinitions) {
                $currentPos = WordType::firstOrCreate( // Find or create Word Type if it does not exist.
                    ['pos' => $partOfSpeech],
                    ['pos_full' => $partOfSpeech],
                );
                $currentPosId = $currentPos->id;

                foreach ($spanishDefinitions['spanish_definitions'] as $spanishDefinition => $englishTranslations) {
                    $definition = preg_replace('/\(|\)/', '', $spanishDefinition); // Remove parentheses from definition.
                    $currentDefinition = Definition::firstOrCreate(
                        [
                            'word_id'      => $currentSpanishWordId,
                            'word_type_id' => $currentPosId,
                            'definition'   => $definition,
                        ],
                    );
                    $currentDefinitionId = $currentDefinition->id;

                    foreach ($englishTranslations as $englishTranslation => $sentences) {
                        $currentEnglishWord = Word::firstOrCreate( // Find or create Word if it does not exist.
                            ['word' => $englishTranslation],
                            ['language_id' => 1],
                        );
                        $currentEnglishWordId = $currentEnglishWord->id;

                        Translation::firstOrCreate(
                            [
                                'word_type_id'       => $currentPosId,
                                'source_word_id'     => $currentSpanishWordId,
                                'translated_word_id' => $currentEnglishWordId,
                                'definition_id'      => $currentDefinitionId,
                            ],
                        );

                        foreach ($sentences['sentences'] as $language => $sentence) {
                            $languageCodes = ['english' => 1 ,'spanish' => 2];
                            Sentence::firstOrCreate(
                                [
                                    'sentence'        => $sentence,
                                    'word_type_id'    => $currentPosId,
                                    'language_id'     => $languageCodes[$language],
                                    'english_word_id' => $currentEnglishWordId,
                                    'spanish_word_id' => $currentSpanishWordId,
                                ]
                            );
                        }
                    }
                }
            }
        }
        return response(['message' => 'Records added successfully'], 201);
    }

    public function getFromDictionary($word)
    {
        return DB::table('words', 'w')
            ->select('w.word as spanish_translation', 'w2.word as english_translation', 'df.definition', 'wt.pos_full as part_of_speech', 'st.language_id as language', 'st.sentence')
            ->leftJoin('definitions as df', 'w.id', '=', 'df.word_id')
            ->leftJoin('word_types as wt', 'df.word_type_id', '=', 'wt.id')
            ->leftJoin('translations as tr', function(JoinClause $join) {
                $join->on('w.id', '=', 'tr.source_word_id')
                     ->on('wt.id', '=', 'tr.word_type_id')
                     ->on('df.id', '=', 'tr.definition_id');
            })
            ->leftJoin('words as w2', 'tr.translated_word_id', '=', 'w2.id')
            ->leftJoin('sentences as st', function(JoinClause $join) {
                $join->on('w.id', '=', 'st.spanish_word_id')
                     ->on('wt.id', '=', 'st.word_type_id')
                     ->on('tr.translated_word_id', '=', 'st.english_word_id');
            })
            ->where('w.word', '=', $word)
            ->get();
    }
}















































