<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Interval
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of hours between every run.
    |
    */

    'interval' => env('TMDB_INTERVAL', 6),


    /*
    |--------------------------------------------------------------------------
    | Count Of Records
    |--------------------------------------------------------------------------
    |
    | Here you may specify the records count Of Movies.
    |
    */

    'count' => env('TMDB_COUNT', 100),

    'api_key' => env('TMDB_API_KEY'),

];
