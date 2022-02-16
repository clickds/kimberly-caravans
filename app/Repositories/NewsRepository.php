<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class NewsRepository
 * @package App\Repositories
 */
class NewsRepository
{

    /**
     * @param int $number
     * @param string $orderBy
     * @param string $order
     * @return Collection|null
     */
    public function getAllPublished(int $number = 10, string $orderBy = 'created_at', string $order = 'DESC') : ?Collection
    {
        $query = News::where('published',true)
            ->orderBy($orderBy,$order)
            ->limit($number);


        $news = $query->get();

        return $news ?? null;
    }
}
