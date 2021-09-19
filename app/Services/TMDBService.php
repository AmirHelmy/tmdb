<?php

namespace App\Services;

use GuzzleHttp\Client;

class TMDBService
{
    private $baseURL = 'https://api.themoviedb.org/3';
    private $page = 1;

    public function fetchTopRated()
    {
        return $this->call('/movie/top_rated');
    }

    public function fetchNowPlaying()
    {
        return $this->call('/movie/now_playing');
    }

    public function featchCategories()
    {
        return $this->call('/genre/movie/list');
    }

    private function call($uri)
    {
        $client = new Client();
        $res = $client->request(
            'GET',
            $this->baseURL . $uri,
            $this->getRequestOptions()
        );
        return json_decode($res->getBody(), TRUE);
    }

    private function getRequestOptions()
    {
        return [
            'query' => [
                'api_key' => config('tmdb.api_key'),
                'language' => 'en-US',
                'page' => $this->page
            ]
        ];
    }

    public function setPage($page)
    {
        $this->page = $page;
    }
}
