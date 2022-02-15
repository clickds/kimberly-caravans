<?php

namespace App\Repositories;

use App\Models\Caravan;

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
        $query = DB::table('caravan')
            ->join('type','caravan.type_id', '=', 'type.id')
            ->join('category','caravan.category_id', '=', 'category.id')
            ->where('type.name','=', 'Used')
            ->where('category.name', '=', ucwords(strtolower($category)))
            ->orderBy($orderBy,$order);

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

        $query = DB::table('caravan')
            ->join('type','caravan.type_id', '=', 'type.id')
            ->join('category','caravan.category_id', '=', 'category.id')
            ->where('type.name','=', 'New')
            ->where('category.name', '=', ucwords(strtolower($category)))
            ->orderBy($orderBy,$order);

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
        $query = DB::table('caravan')
            ->join('category','caravan.category_id', '=', 'category.id')
            ->where('category.name', '=', ucwords(strtolower($category)))
            ->orderBy($orderBy,$order);

        if($number !== null)
            $query->limit($number);


        // fetch
        $items = $query->get();

        return $items ?? null;
    }

}
