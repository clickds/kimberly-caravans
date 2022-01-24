<?php

namespace App\Models\Traits;

use App\Models\Button;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Buttonable
{
    public function buttons(): MorphMany
    {
        return $this->morphMany(Button::class, 'buttonable');
    }
}
