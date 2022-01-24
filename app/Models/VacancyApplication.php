<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jdexx\EloquentRansack\Ransackable;

class VacancyApplication extends Model
{
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'address',
        'nationality',
        'require_permission_to_work_in_uk',
        'number_of_dependents',
        'telephone_number',
        'mobile_number',
        'have_own_transport',
        'currently_employed_by',
        'current_position',
        'seeking_employment_change_reason',
        'weeks_notice_required',
        'conviction_details',
        'marquis_employee_reference_name',
        'booked_holiday_details',
        'have_any_disabilities',
        'disability_details',
        'wear_glasses_or_contacts',
        'glasses_or_contacts_details',
        'receiving_medical_treatment',
        'medical_treatment_details',
        'smoker',
        'courses_and_certificates',
        'practical_experience',
        'hobbies_and_interests',
        'references',
        'dealer_id',
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function employmentHistory(): HasMany
    {
        return $this->hasMany(VacancyApplicationEmploymentHistory::class);
    }
}
