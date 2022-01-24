<?php

namespace App\Models;

use App\Models\Traits\Expirable;
use App\Models\Traits\Pageable;
use App\Models\Traits\Publishable;
use App\Presenters\VacancyPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jdexx\EloquentRansack\Ransackable;
use McCool\LaravelAutoPresenter\HasPresenter;

class Vacancy extends Model implements HasPresenter
{
    use Publishable;
    use Expirable;
    use Pageable;
    use Ransackable;

    protected $table = 'vacancies';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'short_description',
        'description',
        'salary',
        'published_at',
        'expired_at',
        'notification_email_address',
    ];

    public function scopeDisplayable(Builder $query): Builder
    {
        return $query->published()->notExpired();
    }

    public function dealers(): BelongsToMany
    {
        return $this->belongsToMany(Dealer::class, 'vacancy_dealers');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(VacancyApplication::class);
    }

    public function sluggableSources(): array
    {
        return [
            'title',
            'sluggable_published_at',
        ];
    }

    public function getPresenterClass(): string
    {
        return VacancyPresenter::class;
    }
}
