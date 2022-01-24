<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VacancyApplicationEmploymentHistory extends Model
{
    protected $table = 'vacancy_application_employment_history';

    /**
     * @var array
     */
    protected $fillable = [
        'position',
        'start_date',
        'end_date',
        'employer_details',
        'reasons_for_leaving',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function vacancyApplication(): BelongsTo
    {
        return $this->belongsTo(VacancyApplication::class);
    }
}
