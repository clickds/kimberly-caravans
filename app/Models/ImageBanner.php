<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Interfaces\HasButtons;
use App\Models\Interfaces\HasThemedProperties;
use App\Models\Traits\Buttonable;
use App\Models\Traits\Expirable;
use App\Models\Traits\Liveable;
use App\Models\Traits\Orderable;
use App\Models\Traits\Publishable;
use App\Models\Traits\ThemedProperties;
use App\Presenters\ImageBannerPresenter;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;

class ImageBanner extends Model implements HasButtons, HasMedia, HasPresenter, HasThemedProperties
{
    use Buttonable;
    use InteractsWithMedia;
    use Ransackable;
    use ThemedProperties;
    use Orderable;
    use Publishable;
    use Expirable;
    use Liveable;

    public const BACKGROUND_COLOURS = HasThemedProperties::COLOURS;
    public const TEXT_COLOURS = HasThemedProperties::COLOURS;

    public const ICON_NONE = 'None';
    public const ICON_CARAVAN = 'Caravan';
    public const ICON_MOTORHOME = 'Motorhome';
    public const ICON_BOTH = 'Both';

    public const ICONS = [
        self::ICON_NONE,
        self::ICON_CARAVAN,
        self::ICON_MOTORHOME,
        self::ICON_BOTH,
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

    /**
     * @var array
     */
    protected $fillable = [
        'content',
        'content_background_colour',
        'content_text_colour',
        'icon',
        'position',
        'text_alignment',
        'title',
        'title_background_colour',
        'title_text_colour',
        'published_at',
        'expired_at',
        'live',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'position' => 0,
    ];

    protected $casts = [
        'live' => 'bool',
    ];

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class);
    }

    public function setContentBackgroundColourAttribute(string $value): void
    {
        if ($this->isInvalidBackgroundColour($value)) {
            throw new InvalidArgumentException('Invalid colour');
        }
        $this->attributes['content_background_colour'] = $value;
    }

    public function setTitleBackgroundColourAttribute(string $value): void
    {
        if ($this->isInvalidBackgroundColour($value)) {
            throw new InvalidArgumentException('Invalid colour');
        }
        $this->attributes['title_background_colour'] = $value;
    }

    public function setContentTextColourAttribute(string $value): void
    {
        if ($this->isInvalidTextColour($value)) {
            throw new InvalidArgumentException('Invalid colour');
        }
        $this->attributes['content_text_colour'] = $value;
    }

    public function setTitleTextColourAttribute(string $value): void
    {
        if ($this->isInvalidTextColour($value)) {
            throw new InvalidArgumentException('Invalid colour');
        }
        $this->attributes['title_text_colour'] = $value;
    }

    private function isInvalidBackgroundColour(string $value): bool
    {
        if (in_array($value, array_keys(self::BACKGROUND_COLOURS))) {
            return false;
        }
        return true;
    }

    private function isInvalidTextColour(string $value): bool
    {
        if (in_array($value, array_keys(self::TEXT_COLOURS))) {
            return false;
        }
        return true;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(80)
            ->height(80);

        $this->addMediaConversion('responsive')->withResponsiveImages();
    }

    public function getPresenterClass(): string
    {
        return ImageBannerPresenter::class;
    }
}
