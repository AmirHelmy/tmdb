<?php

namespace App\Repositories;

use App\Http\Resources\MovieResource;
use App\Movie;

class MovieRepository
{

    public function create($movies, $batch)
    {
        $movies = $this->neglectDuplication($movies);
        $rows = $this->transformData($movies, $batch);
        foreach ($rows as $row) {
            $movie = Movie::query()->create($row['inputs']);
            $movie->categories()->attach($row['categories_ids']);
        }
    }

    public function list()
    {
        $movies = Movie::query()
            ->when(request('category_id'), function ($query) {
                return $query->whereHas('categories', function ($query) {
                    return $query->where('categories.id', request('category_id'));
                });
            })
            ->when(request()->has('popular|desc'), function ($query) {
                return $query->orderBy('popularity', 'desc');
            })
            ->when(request()->has('popular|asc'), function ($query) {
                return $query->orderBy('popularity', 'asc');
            })
            ->when(request()->has('rated|desc'), function ($query) {
                return $query->orderBy('rate', 'desc');
            })
            ->when(request()->has('rated|asc'), function ($query) {
                return $query->orderBy('rate', 'asc');
            })
            ->paginate(20);

        return MovieResource::collection($movies);
    }

    private function transformData($movies, $batch)
    {
        return array_map(function ($movie) use ($batch) {
            return [
                'inputs' => [
                    'tmdb_id' => $movie['id'],
                    'title' => $movie['title'],
                    'popularity' => $movie['popularity'],
                    'rate' => $movie['vote_average'],
                    'insertion_batch' => $batch,
                ],
                'categories_ids' => $movie['genre_ids']
            ];
        }, $movies);
    }

    private function neglectDuplication($movies)
    {
        $tmdbIds = array_column($movies, 'id');

        $existIds = Movie::query()
            ->select('tmdb_id')
            ->whereIn('tmdb_id', $tmdbIds)
            ->pluck('tmdb_id')
            ->toArray();

        return array_filter($movies, function ($movie) use ($existIds) {
            return !in_array($movie['id'], $existIds);
        });
    }
}
