<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jdexx\EloquentRansack\Ransackable;

class EmailRecipient extends Model
{
    use Ransackable;

    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'name',
        'receives_vehicle_enquiry',
    ];

    public function scopeReceivesVehicleEnquiry(Builder $query, bool $value = true): Builder
    {
        return $query->where('receives_vehicle_enquiry', $value);
    }

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class);
    }
}
