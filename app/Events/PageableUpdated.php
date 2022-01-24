<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PageableUpdated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var Model
     */
    private $pageable;

    public function __construct(Model $pageable)
    {
        $this->pageable = $pageable;
    }

    public function getPageable(): Model
    {
        return $this->pageable;
    }
}
