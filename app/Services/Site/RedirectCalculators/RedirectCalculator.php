<?php

namespace App\Services\Site\RedirectCalculators;

use Illuminate\Http\RedirectResponse;

interface RedirectCalculator
{
    public function calculateRedirect(): ?RedirectResponse;
}
