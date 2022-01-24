<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VehicleEnquiry extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'county',
        'email',
        'first_name',
        'help_methods',
        'interests',
        'marketing_preferences',
        'message',
        'phone_number',
        'surname',
        'title',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'help_methods' => 'array',
        'interests' => 'array',
        'marketing_preferences' => 'array',
    ];

    public function dealers(): BelongsToMany
    {
        return $this->belongsToMany(Dealer::class, 'vehicle_enquiries_preferred_dealers')->withTimestamps();
    }

    public function name(): string
    {
        $parts = [
            $this->first_name,
            $this->surname,
        ];

        return implode(' ', $parts);
    }
}
