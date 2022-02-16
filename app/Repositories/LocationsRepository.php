<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Support\Collection;

/**
 * Class LocationsRepository
 * @package App\Repositories
 */
class LocationsRepository
{
    /**
     * @param string $orderBy
     * @param string $order
     * @return Collection|null
     */
    public function getAll(string $orderBy = 'name', string $order = 'ASC') : ?Collection
    {
        $query = Location::orderBy($orderBy,$order)->get();

        return $query ?? null;
    }
}
