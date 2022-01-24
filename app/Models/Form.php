<?php

namespace App\Models;

use App\Models\Traits\Featureable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Jdexx\EloquentRansack\Ransackable;

class Form extends Model
{
    use Featureable;
    use Ransackable;

    public const TYPE_STANDARD = 'Standard Form';
    public const TYPE_PART_EXCHANGE = 'Part Exchange Form';
    public const TYPE_NEWSLETTER_SIGN_UP = 'Newsletter Sign Up';

    public const VALID_TYPES = [
        self::TYPE_STANDARD,
        self::TYPE_PART_EXCHANGE,
        self::TYPE_NEWSLETTER_SIGN_UP,
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'type',
        'crm_list',
        'email_to',
        'name',
        'successful_submission_message',
    ];

    public function carbonCopyRecipients(): BelongsToMany
    {
        return $this->belongsToMany(EmailRecipient::class);
    }

    public function fieldsets(): BelongsToMany
    {
        return $this->belongsToMany(Fieldset::class)->withPivot('position')
            ->orderBy('fieldset_form.position', 'asc');
    }

    public function panels(): MorphMany
    {
        return $this->morphMany(Panel::class, 'featureable');
    }

    public function areas(): HasManyThrough
    {
        return $this->hasManyThrough(
            Area::class,
            Panel::class,
            'featureable_id',
            'id',
            'id',
            'area_id',
        );
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }
}
