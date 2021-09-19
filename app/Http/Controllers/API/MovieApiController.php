<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MovieRepository;

class MovieApiController extends Controller
{
    public function index(MovieRepository $movieRepository)
    {
        return $movieRepository->list();
    }
}
