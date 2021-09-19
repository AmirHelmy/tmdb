<?php

namespace App\Repositories;

use App\Category;
use App\Movie;

class CategoryRepository
{

    public function create($categories)
    {
        $categories = $this->neglectDuplication($categories);
        $rows = $this->transformData($categories);
        Category::insert($rows);
    }

    private function transformData($categories)
    {
        return array_map(function ($category) {
            return [
                'id' => $category['id'],
                'name' => $category['name'],
            ];
        }, $categories);
    }

    private function neglectDuplication($categories)
    {
        $tmdbIds = array_column($categories, 'id');

        $existIds = Category::query()
            ->select('id')
            ->whereIn('id', $tmdbIds)
            ->pluck('id')
            ->toArray();

        return array_filter($categories, function ($category) use ($existIds) {
            return !in_array($category['id'], $existIds);
        });
    }
}
