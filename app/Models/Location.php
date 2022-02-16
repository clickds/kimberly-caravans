<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Location
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $contact_number
 * @property string $lng
 * @property string $lat
 * @property string $heading_1
 * @property string $content_1
 * @property string $heading_2
 * @property string $content_2
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Location extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'locations';

    /**
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
