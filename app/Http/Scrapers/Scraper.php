<?php

namespace App\Http\Scrapers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DictionaryController;
use GuzzleHttp\Client;

class Scraper extends Controller
{
    protected Client $client;

    public function __construct(
        protected DictionaryController $dictionaryController
    )
    {
        $this->client = new Client([
            'base_uri' => 'https://www.ingles.com',
            'timeout' => 2.0
        ]);
    }
}
