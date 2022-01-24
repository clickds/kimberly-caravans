<?php

namespace App\Models;

use App\Presenters\NavigationPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;

class Navigation extends Model implements HasPresenter
{
    use Ransackable;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',
        'site_id',
        'type',
    ];

    public const TYPE_MAIN = 'Main';
    public const TYPE_FULL_MENU = 'Full Menu';
    public const TYPE_FOOTER_MORE = 'Footer More';
    public const TYPE_FOOTER_LEGAL = 'Footer Legal';

    public const NAVIGATION_TYPES = [
        self::TYPE_MAIN => 'Main',
        self::TYPE_FULL_MENU => 'Full Menu',
        self::TYPE_FOOTER_MORE => 'Footer More',
        self::TYPE_FOOTER_LEGAL => 'Footer Legal',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function navigationItems(): HasMany
    {
        return $this->hasMany(NavigationItem::class);
    }

    public static function parseNavigationName(string $string): string
    {
        $tempArr = explode('_', $string);
        $name = ucwords($tempArr[count($tempArr) - 1]);

        return $name;
    }

    public function getPresenterClass(): string
    {
        return NavigationPresenter::class;
    }
}
