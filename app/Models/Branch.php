<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Branch
 * @package App\Models
 * @property int $id
 * @property string $name
 */
class Branch extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'branch';

}
