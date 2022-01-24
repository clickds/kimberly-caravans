<?php

namespace App\Models;

use App\Models\Interfaces\HasThemedProperties;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use App\Models\Traits\Expirable;
use App\Models\Traits\Liveable;
use App\Models\Traits\Publishable;
use App\Models\Traits\ThemedProperties;
use App\Presenters\AreaPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use UnexpectedValueException;

class Area extends Model implements HasPresenter, HasThemedProperties
{
    use Expirable;
    use Liveable;
    use Publishable;
    use ThemedProperties;

    /**
     * @var array
     */
    protected $fillable = [
        'heading_text_alignment',
        'background_colour',
        'columns',
        'expired_at',
        'heading',
        'heading_type',
        'holder',
        'live',
        'name',
        'page_id',
        'position',
        'published_at',
        'width',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'background_colour' => 'white',
        'position' => 0,
    ];

    public const TEXT_ALIGNMENT_LEFT = 'text-left';
    public const TEXT_ALIGNMENT_CENTRE = 'text-center';
    public const TEXT_ALIGNMENT_RIGHT = 'text-right';
    public const TEXT_ALIGNMENT_JUSTIFY = 'text-justify';

    public const TEXT_ALIGNMENTS = [
        self::TEXT_ALIGNMENT_LEFT => 'Left',
        self::TEXT_ALIGNMENT_CENTRE => 'Centre',
        self::TEXT_ALIGNMENT_RIGHT => 'Right',
        self::TEXT_ALIGNMENT_JUSTIFY => 'Justify',
    ];

    public const BACKGROUND_COLOURS = HasThemedProperties::COLOURS;

    public const HEADING_TYPES = [
        'h1',
        'h2',
        'h3',
    ];

    public const COLUMNS = [
        1,
        2
    ];

    public const STANDARD_WIDTH = 'standard';
    public const FLUID_WIDTH = 'fluid';
    public const WIDTHS = [
        self::STANDARD_WIDTH => 'Standard',
        self::FLUID_WIDTH => 'Fluid',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function panels(): HasMany
    {
        return $this->hasMany(Panel::class)->orderBy('position', 'asc');
    }

    public function scopeLive(Builder $query, bool $live = true): Builder
    {
        return $query->where('live', $live);
    }

    /**
     * @param mixed $value
     */
    public function setBackgroundColourAttribute($value): void
    {
        if (!in_array($value, array_keys(static::BACKGROUND_COLOURS))) {
            throw new UnexpectedValueException('Background colour value invalid');
        }
        $this->attributes['background_colour'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setHeadingTypeAttribute($value): void
    {
        if (!in_array($value, static::HEADING_TYPES)) {
            throw new UnexpectedValueException('heading type value invalid');
        }
        $this->attributes['heading_type'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setColumnsAttribute($value): void
    {
        if (!in_array($value, static::COLUMNS)) {
            throw new UnexpectedValueException('Columns value invalid');
        }
        $this->attributes['columns'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setWidthAttribute($value): void
    {
        if (!in_array($value, array_keys(static::WIDTHS))) {
            throw new UnexpectedValueException('Widths value invalid');
        }
        $this->attributes['width'] = $value;
    }

    public function getPresenterClass(): string
    {
        return AreaPresenter::class;
    }

    public function isStandardWidth(): bool
    {
        return $this->width === Area::STANDARD_WIDTH;
    }
}
