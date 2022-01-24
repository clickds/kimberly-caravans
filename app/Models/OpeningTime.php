<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpeningTime extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'day',
        'opens_at',
        'closes_at',
        'closed',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'opens_at' => 'datetime:H:i',
        'closes_at' => 'datetime:H:i',
        'closed' => 'boolean',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function scopeForDay(Builder $query, string $timezone = 'UTC', int $day = null): Builder
    {
        if (is_null($day)) {
            $day = Carbon::now($timezone)->dayOfWeek;
        }
        return $query->where('day', $day);
    }

    public function isOpen(string $timezone = 'UTC'): bool
    {
        if (true === $this->closed) {
            return false;
        }

        $now = Carbon::now($timezone);
        $opensAt = $this->opensAtForTimezone($timezone);
        $closedAt = $this->closedAtForTimezone($timezone);
        if ($now->greaterThanOrEqualTo($opensAt) && $now->lessThanOrEqualTo($closedAt)) {
            return true;
        }
        return false;
    }

    public function opensAtForTimezone(string $timezone): ?Carbon
    {
        if (is_null($this->opens_at)) {
            return null;
        }
        return $this->dateTimeinTimezone($this->opens_at, $timezone);
    }

    public function closedAtForTimezone(string $timezone): ?Carbon
    {
        if (is_null($this->closes_at)) {
            return null;
        }
        return $this->dateTimeinTimezone($this->closes_at, $timezone);
    }

    public function dayName(): string
    {
        $days = Carbon::getDays();
        if (array_key_exists($this->day, $days)) {
            return $days[$this->day];
        }
        return 'No Day Set';
    }

    public function openingHours(): string
    {
        $times = [];
        if ($this->opens_at) {
            $times[] = $this->formatTime($this->opens_at);
        }
        if ($this->closes_at) {
            $times[] = $this->formatTime($this->closes_at);
        }

        return implode(' - ', $times);
    }

    private function formatTime(Carbon $time): string
    {
        return $time->format('G:iA');
    }

    private function dateTimeinTimezone(Carbon $dateTime, string $timezone): Carbon
    {
        $tzDate = Carbon::now($timezone);
        $tzDate->hour = $dateTime->hour;
        $tzDate->minute = $dateTime->minute;
        return $tzDate;
    }
}
