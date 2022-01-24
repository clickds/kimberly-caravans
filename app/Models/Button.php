<?php

namespace App\Models;

use App\Presenters\ButtonPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use InvalidArgumentException;
use McCool\LaravelAutoPresenter\HasPresenter;

class Button extends Model implements HasPresenter
{
    /**
     * An array of tailwind defined colours => humanised names
     */
    public const COLOURS = [
        'endeavour' => 'Sky Blue',
        'regal-blue' => 'Dark Blue',
        'shiraz' => 'Burgundy',
        'monarch' => 'Dark Burgundy',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'colour',
        'external_url',
        'link_page_id',
        'name',
        'position',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'position' => 0,
    ];

    public function buttonable(): MorphTo
    {
        return $this->morphTo();
    }

    public function linkPage(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'link_page_id');
    }

    /**
     * @param mixed $value
     */
    public function setColourAttribute($value): void
    {
        if (!array_key_exists($value, self::COLOURS)) {
            throw new InvalidArgumentException('Invalid colour');
        }
        $this->attributes['colour'] = $value;
    }

    public function humanisedColourName(): string
    {
        return self::COLOURS[$this->colour];
    }

    public function getPresenterClass(): string
    {
        return ButtonPresenter::class;
    }
}
