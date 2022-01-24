<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Motorhome;

class MotorhomeSaved
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var Motorhome
     */
    private $motorhome;

    public function __construct(Motorhome $motorhome)
    {
        $this->motorhome = $motorhome;
    }

    public function getMotorhome(): Motorhome
    {
        return $this->motorhome;
    }
}
