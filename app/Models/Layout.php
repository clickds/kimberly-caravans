<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Jdexx\EloquentRansack\Ransackable;

class Layout extends Model
{
    use Ransackable;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'code',
        'name',
    ];

    public function motorhomeStockItems(): Relation
    {
        return $this->hasMany(MotorhomeStockItem::class);
    }

    public function caravanStockItems(): Relation
    {
        return $this->hasMany(CaravanStockItem::class);
    }

    public function nameWithCode(): string
    {
        return implode(' - ', [$this->name, $this->code]);
    }
}
