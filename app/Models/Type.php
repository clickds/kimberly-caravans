<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Type
 * @package App\Models
 * @property int $id
 * @property string $name
 */
class Type extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'type';

}
