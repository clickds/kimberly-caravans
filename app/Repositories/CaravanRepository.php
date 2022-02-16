<?php

namespace App\Repositories;

use App\Models\Caravan;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class CaravanRepository
 * @package App\Repositories
 */
class CaravanRepository
{

    /**
     * @param string $category
     * @param string $orderBy
     * @param string $order
     * @param int|null $number
     * @return Collection|null
     */
    public function getUsedByCategory(string $category = 'Caravan', string $orderBy = 'caravan.id', string $order = 'DESC', ?int $number = null) : ?Collection
    {

        $query = Caravan::query()
            ->whereRelation('type',function(Builder $builder) {
                $builder->where('name','Used');
            })
            ->whereRelation('category',function(Builder $builder) use ($category) {
                $builder->where('name',ucwords(strtolower($category)));
            })->orderBy($orderBy,$order);

        if($number !== null)
            $query->limit($number);


        // fetch
        $items = $query->get();

        return $items ?? null;

    }

    /**
     * @param string $category
     * @param string $orderBy
     * @param string $order
     * @param int|null $number
     * @return Collection|null
     */
    public function getNewByCategory(string $category = 'Caravan', string $orderBy = 'caravan.id', string $order = 'DESC', ?int $number = null) : ?Collection
    {

        $query = Caravan::query()
            ->whereRelation('type',function(Builder $builder) {
                $builder->where('name','New');
            })
            ->whereRelation('category',function(Builder $builder) use ($category) {
                $builder->where('name',ucwords(strtolower($category)));
            })->orderBy($orderBy,$order);

        if($number !== null)
            $query->limit($number);

        if($number !== null)
            $query->limit($number);

        // fetch
        $items = $query->get();

        return $items ?? null;
    }

    /**
     * @param string $category
     * @param string $orderBy
     * @param string $order
     * @param int|null $number
     * @return Collection|null
     */
    public function getAllByCategory(string $category = 'Caravan', string $orderBy = 'caravan.id', string $order = 'DESC', ?int $number = null) : ?Collection
    {
        $query = Caravan::query()
            ->whereRelation('category',function(Builder $builder) use ($category) {
                $builder->where('name',ucwords(strtolower($category)));
            })->orderBy($orderBy,$order);

        if($number !== null)
            $query->limit($number);

        if($number !== null)
            $query->limit($number);


        // fetch
        $items = $query->get();

        return $items ?? null;
    }

    /**
     * @param array $filters
     * @return Collection|null
     */
    public function search(array $filters) : ?Collection
    {

        return null;
    }

}
