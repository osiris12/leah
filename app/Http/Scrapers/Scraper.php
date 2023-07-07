<?php

namespace App\Http\Scrapers;

use GuzzleHttp\Client;

class Scraper
{
    protected Client $client;
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://www.ingles.com',
            'timeout' => 2.0
        ]);
    }
}
