<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class SiteSetting extends Model
{
    public const SETTING_LATEST_OFFERS_ADDED_TIME_FRAME = 'Latest offers added time frame';
    public const SETTING_FORM_SUBMISSION_DEFAULT_EMAIL_ADDRESS = 'Form submission default email address';

    public const VALID_SETTINGS = [
        self::SETTING_LATEST_OFFERS_ADDED_TIME_FRAME,
        self::SETTING_FORM_SUBMISSION_DEFAULT_EMAIL_ADDRESS,
    ];

    protected $fillable = [
        'name',
        'description',
        'value',
    ];

    public function setNameAttribute(string $name): void
    {
        if (!in_array($name, self::VALID_SETTINGS)) {
            throw new InvalidArgumentException('Invalid site setting name');
        }

        $this->attributes['name'] = $name;
    }

    public function scopeLatestOffersAddedTimeFrame(Builder $query): Builder
    {
        return $query->where('name', self::SETTING_LATEST_OFFERS_ADDED_TIME_FRAME);
    }

    public function scopeFormSubmissionDefaultEmailAddress(Builder $query): Builder
    {
        return $query->where('name', self::SETTING_FORM_SUBMISSION_DEFAULT_EMAIL_ADDRESS);
    }
}
