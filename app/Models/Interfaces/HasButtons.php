<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasButtons
{
    public function buttons(): MorphMany;
}
