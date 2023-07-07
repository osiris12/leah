<?php

namespace App\Http\Scrapers;

use Psr\Http\Message\StreamInterface;
use SebastianBergmann\Invoker\Exception;
use Symfony\Component\DomCrawler\Crawler;

class InglesScraper extends Scraper
{
    /**
     * Gets the translation (traductor) page from ingles.com, i.e.(https://www.ingles.com/traductor/conforme)
     * @param string $word
     * @return StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTranslationPage(string $word): StreamInterface
    {
        return $this->client->request('GET', "/traductor/$word")->getBody();
    }

    public function parseTranslationPage()
    {
        $html = $this->getTranslationPage('asustar');
        $this->parsePartsOfSpeech($html);

    }

    /**
     * Sections in translation page are split by parts of speech, i.e.
     * Adjective, Noun, Verb, etc.. Each section contains a list
     * of the translated English words.
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function parsePartsOfSpeech(StreamInterface $html)
    {
        // Part of Speech class = .hWzdmlHx
        // Sentence Example (Spanish) = .S7halQ2C
        // Sentence Example (English) = .msZ0iHzp

        $crawler = new Crawler($html->getContents());


        $spanishWords = $crawler
            ->filter("#dictionary-neodict-es > div")
            ->each(function (Crawler $wordCrawler) {
                $spanishWord = $wordCrawler->filter(".jXNWfTzf")->text();
                $pos = $wordCrawler
                    ->filter(".k5rFFEq7")
                    ->each(function (Crawler $partOfSpeechCrawler) {
                        $currentPos = $partOfSpeechCrawler->filter(".hWzdmlHx")->text();
                        $englishWords = $partOfSpeechCrawler
                            ->filter(".YR6epHeU")
                            ->each(function (Crawler $englishWordCrawler) use($partOfSpeechCrawler) {
                                $sentences = [];
                                $sentences['spanish'] = $partOfSpeechCrawler->filter(".S7halQ2C")->text();
                                $sentences['english'] = $partOfSpeechCrawler->filter(".msZ0iHzp")->text();
                                return [$englishWordCrawler->text() => $sentences];
                        });
                        return [$currentPos => $englishWords];
//                try {
//                    $pos = $partOfSpeechCrawler
//                        ->filter(".hWzdmlHx")
//                        ->each(function (Crawler $crawler) {
//                        $words = $crawler
//                            ->filter(".YR6epHeU")
//                            ->each(function(Crawler $cr) {
//                            return $cr->text();
//                        });
//                        return [$crawler->text() => $words];
//                    });
//                    return [$spanishWord => $pos];
//                } catch (\Exception $e) {  }
            });
                return [$spanishWord => $pos];
        });

        echo json_encode($spanishWords);exit;

        $englishWords = $crawler
            ->filter("#dictionary-neodict-es")
            ->filter(".AJ6Kb8A8")->each(function(Crawler $nodee) {
                return $nodee
                    ->filter('.i57Bf7Dj + span a.YR6epHeU')
                    ->each(function(Crawler $node) {
                        try {
                            return $node->filter('.YR6epHeU')->text();
                        } catch (\Exception $e) {
                            return 'Empty';
                        }
                    });
        });

        $sentences = $crawler
            ->filter("#dictionary-neodict-es")
            ->filter(".QkSyASiy .lbHJ7w6W ")->each(function(Crawler $crawl) {
                return [
                    "spanish" => $crawl->filter(".S7halQ2C")->text(),
                    "english" => $crawl->filter(".msZ0iHzp")->text(),
                ];
        });



        echo json_encode([$spanishWords, $englishWords, $sentences]);exit;
        return $translation;
    }

    public function getEnglishWords(Crawler $crawler)
    {

    }

}
