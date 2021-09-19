<?php

namespace App\Jobs;

use App\Movie;
use App\Services\TMDBService;
use Illuminate\Bus\Queueable;
use App\Repositories\MovieRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InsertMovies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $page;

    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TMDBService $service, MovieRepository $movieRepository)
    {
        $batch = time();
        $page = 1;
        $required = config('tmdb.count');
        dump($required);
        do {
            $service->setPage($page);
            $nowPlayingMovies = $service->fetchNowPlaying();
            $movieRepository->create($nowPlayingMovies['results'], $batch);

            $topRatedMovies = $service->fetchTopRated();
            $movieRepository->create($topRatedMovies['results'], $batch);
            $page++;
        } while (Movie::query()->where('insertion_batch', $batch)->count() < $required);
    }
}
