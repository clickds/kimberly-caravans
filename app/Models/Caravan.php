<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Caravan
 * @package App\Models
 * @property int $stock
 * @property int $branch_id
 * @property int $category_id
 * @property int $type_id
 * @property string $reg
 * @property string $make
 * @property string $model
 * @property string $specification
 * @property string $derivative
 * @property string $engine_size
 * @property string $engine_type
 * @property string $transmission
 * @property string $colour
 * @property int $year
 * @property int $mileage
 * @property boolean $commercial
 * @property double $sales_siv
 * @property double $retail
 * @property double $web_price
 * @property string $sub_heading;
 * @property string $advertising_notes
 * @property string $manager_comments
 * @property double $previous_price
 * @property double $guide_retail_price
 * @property boolean $available_for_sale
 * @property boolean $advertised_on_own_website
 * @property int $berths
 * @property int $axles
 * @property string $layout_type
 * @property double $width
 * @property double $length
 * @property double $height
 * @property int $kimberley_unit_id
 * @property \DateTime $kimberley_date_updated
 *
 */
class Caravan extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'caravan';

    /**
     * @var string[]
     */
    protected $casts = [
        'kimberley_date_updated' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'web_price' => 'decimal: 2',
        'previous_price' => 'decimal: 2'
    ];


    /**
     * @return BelongsTo
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');

    }


    /**
     * @return BelongsTo
     */
    public function type() : BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }
    

}
