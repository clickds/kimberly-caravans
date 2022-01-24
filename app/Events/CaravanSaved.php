<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Caravan;

class CaravanSaved
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var Caravan
     */
    private $caravan;

    public function __construct(Caravan $caravan)
    {
        $this->caravan = $caravan;
    }

    public function getCaravan(): Caravan
    {
        return $this->caravan;
    }
}
