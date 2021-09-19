<?php

namespace App\Console\Commands;

use App\Jobs\InsertMovies;
use App\Jobs\SyncCategories;
use Illuminate\Console\Command;

class GetMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmdb:fetch_movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        SyncCategories::dispatch();
        InsertMovies::dispatch();
    }
}