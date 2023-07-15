<?php

namespace App\Http\Scrapers;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\DomCrawler\Crawler;

class InglesScraper extends Scraper
{

    public function parseTranslationPage(string $word)
    {
        $html = $this->client->request('GET', "/traductor/$word")->getBody();
        return $this->scrapeTranslationData($html, $word);
    }

    /**
     * Sections in translation page are split by parts of speech, i.e.
     * Adjective, Noun, Verb, etc.. Each section contains a list
     * of the translated English words.
     * @return string|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function scrapeTranslationData(StreamInterface $html, $word): array|bool
    {
        $crawler = new Crawler($html->getContents());
        $searchWord = $crawler->filter("#dictionary-neodict-es > div");
        if ($searchWord->count() <= 0) {
            Log::alert("$word not found.");
            return false;
        }

        $spanishWords = [];
        $searchWord->each(function (Crawler $wordCrawler) use (&$spanishWords) {
                try {
                    $spanishWord = $wordCrawler->filter(".jXNWfTzf")->text();
                    $pos = [];
                    $wordCrawler
                        ->filter(".k5rFFEq7")
                        ->each(function (Crawler $partOfSpeechCrawler) use($spanishWord, &$pos) {
                            try {
                                $currentPos = $partOfSpeechCrawler->filter(".hWzdmlHx")->text();
                                $spanishDefinitions = [];
                                $partOfSpeechCrawler
                                    ->filter(".AJ6Kb8A8 > .lbHJ7w6W")
                                    ->each(function (Crawler $englishWordCrawler) use($currentPos, $spanishWord, &$spanishDefinitions) {
                                        try {
                                            $spanishDefinition = $englishWordCrawler->filter(".UlJJEaZY")->text();
                                            $englishWords = [];

                                            $englishWordCrawler->filter(".RiMg1_4r > .lbHJ7w6W")->each(function(Crawler $transCrawler) use (&$englishWords) {
                                                try {
                                                    $englishWord = $transCrawler->filter(".YR6epHeU")->text();
                                                    $sentences = [];
                                                    $sentences['spanish'] = $transCrawler->filter(".S7halQ2C")->text();
                                                    $sentences['english'] = $transCrawler->filter(".msZ0iHzp")->text();
                                                    $englishWords[$englishWord] = ["sentences" => $sentences];
                                                    return;
                                                } catch (InvalidArgumentException $e) {
                                                    $error = "No direct translation for ($spanishWord) with part of speech of ($currentPos).";
                                                    Log::alert($error);
                                                }
                                            });

                                            $spanishDefinitions[$spanishDefinition] = $englishWords;
                                        } catch (InvalidArgumentException $e) {
                                            $error = "No direct translation for ($spanishWord) with part of speech of ($currentPos).";
                                            Log::alert($error);
                                        }
                                    });
                                $pos[$currentPos] = ['spanish_definitions' => $spanishDefinitions];
                                return;
                            } catch (\Exception $e) {
                                $error = "No direct translation for ($spanishWord). ";
                                Log::alert($error);
                            }
                        });
                    $spanishWords[$spanishWord] = ["parts_of_speech" => $pos];
                    return;
                } catch (\Exception $e) {
                    Log::alert($e->getMessage());
                }
            });

        $this->dictionaryController->addToDictionary($spanishWords);

        return $spanishWords;
    }

}
