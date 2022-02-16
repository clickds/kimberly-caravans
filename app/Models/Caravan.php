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
    
    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }


    /**
     * @param int $stock
     * @return $this
     */
    public function setStock(int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    /**
     * @return int
     */
    public function getBranchId(): int
    {
        return $this->branch_id;
    }


    /**
     * @param int $branch_id
     * @return $this
     */
    public function setBranchId(int $branch_id): self
    {
        $this->branch_id = $branch_id;
        return $this;
    }

    /**
     * @param Branch $branch
     * @return $this
     */
    public function setBranch(Branch $branch) : self
    {
        $this->branch_id = $branch->id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }


    /**
     * @param int $category_id
     * @return $this
     */
    public function setCategoryId(int $category_id): self
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @param Category $cat
     * @return $this
     */
    public function setCategory(Category $cat) : self
    {
        $this->category_id = $cat->id;
        return $this;
    }

    /**
     * @return int
     */
    public function getTypeId(): int
    {
        return $this->type_id;
    }


    /**
     * @param int $type_id
     * @return $this
     */
    public function setTypeId(int $type_id): self
    {
        $this->type_id = $type_id;
        return $this;
    }

    /**
     * @param Type $type
     * @return $this
     */
    public function setType(Type $type) : self
    {
        $this->type_id = $type->id;
        return $this;
    }

    /**
     * @return string
     */
    public function getReg(): string
    {
        return $this->reg;
    }


    /**
     * @param string $reg
     * @return $this
     */
    public function setReg(string $reg): self
    {
        $this->reg = $reg;
        return $this;
    }

    /**
     * @return string
     */
    public function getMake(): string
    {
        return $this->make;
    }


    /**
     * @param string $make
     * @return $this
     */
    public function setMake(string $make): self
    {
        $this->make = $make;
        return $this;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }


    /**
     * @param string $model
     * @return $this
     */
    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpecification(): string
    {
        return $this->specification;
    }


    /**
     * @param string $specification
     * @return $this
     */
    public function setSpecification(string $specification): self
    {
        $this->specification = $specification;
        return $this;
    }

    /**
     * @return string
     */
    public function getDerivative(): string
    {
        return $this->derivative;
    }


    /**
     * @param string $derivative
     * @return $this
     */
    public function setDerivative(string $derivative): self
    {
        $this->derivative = $derivative;
        return $this;
    }

    /**
     * @return string
     */
    public function getEngineSize(): string
    {
        return $this->engine_size;
    }


    /**
     * @param string $engine_size
     * @return $this
     */
    public function setEngineSize(string $engine_size): self
    {
        $this->engine_size = $engine_size;
        return $this;
    }

    /**
     * @return string
     */
    public function getEngineType(): string
    {
        return $this->engine_type;
    }


    /**
     * @param string $engine_type
     * @return $this
     */
    public function setEngineType(string $engine_type): self
    {
        $this->engine_type = $engine_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransmission(): string
    {
        return $this->transmission;
    }


    /**
     * @param string $transmission
     * @return $this
     */
    public function setTransmission(string $transmission): self
    {
        $this->transmission = $transmission;
        return $this;
    }

    /**
     * @return string
     */
    public function getColour(): string
    {
        return $this->colour;
    }


    /**
     * @param string $colour
     * @return $this
     */
    public function setColour(string $colour): self
    {
        $this->colour = $colour;
        return $this;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }


    /**
     * @param int $year
     * @return $this
     */
    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return int
     */
    public function getMileage(): int
    {
        return $this->mileage;
    }


    /**
     * @param int $mileage
     * @return $this
     */
    public function setMileage(int $mileage): self
    {
        $this->mileage = $mileage;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCommercial(): bool
    {
        return (bool)$this->commercial;
    }


    /**
     * @param bool $commercial
     * @return $this
     */
    public function setCommercial(bool $commercial): self
    {
        $this->commercial = (int)$commercial;
        return $this;
    }

    /**
     * @return float
     */
    public function getSalesSiv(): float
    {
        return $this->sales_siv;
    }


    /**
     * @param float|int $sales_siv
     * @return $this
     */
    public function setSalesSiv(float $sales_siv = 0): self
    {
        $this->sales_siv = $sales_siv;
        return $this;
    }

    /**
     * @return float
     */
    public function getRetail(): float
    {
        return $this->retail;
    }


    /**
     * @param float|int $retail
     * @return $this
     */
    public function setRetail(float $retail = 0): self
    {
        $this->retail = $retail;
        return $this;
    }

    /**
     * @return float
     */
    public function getWebPrice(): float
    {
        return $this->web_price;
    }

    /**
     * @param float|int $web_price
     * @return $this
     */
    public function setWebPrice(float $web_price = 0): self
    {
        $this->web_price = $web_price;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubHeading(): string
    {
        return $this->sub_heading;
    }


    /**
     * @param string $sub_heading
     * @return $this
     */
    public function setSubHeading(string $sub_heading): self
    {
        $this->sub_heading = $sub_heading;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdvertisingNotes(): string
    {
        return $this->advertising_notes;
    }

    /**
     * @param string $advertising_notes
     * @return $this
     */
    public function setAdvertisingNotes(string $advertising_notes): self
    {
        $this->advertising_notes = $advertising_notes;
        return $this;
    }

    /**
     * @return string
     */
    public function getManagerComments(): string
    {
        return $this->manager_comments;
    }


    /**
     * @param string $managerComments
     * @return $this
     */
    public function setManagerComments(string $managerComments): self
    {
        $this->manager_comments = $managerComments;
        return $this;
    }

    /**
     * @return float
     */
    public function getPreviousPrice(): float
    {
        return $this->previous_price;
    }


    /**
     * @param float $previous_price
     * @return $this
     */
    public function setPreviousPrice(float $previous_price): self
    {
        $this->previous_price = $previous_price;
        return $this;
    }

    /**
     * @return float
     */
    public function getGuideRetailPrice(): float
    {
        return $this->guide_retail_price;
    }


    /**
     * @param float $guide_retail_price
     * @return $this
     */
    public function setGuideRetailPrice(float $guide_retail_price): self
    {
        $this->guide_retail_price = $guide_retail_price;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAvailableForSale(): bool
    {
        return $this->available_for_sale;
    }


    /**
     * @param bool $available_for_sale
     * @return $this
     */
    public function setAvailableForSale(bool $available_for_sale): self
    {
        $this->available_for_sale = (int)$available_for_sale;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdvertisedOnOwnWebsite(): bool
    {
        return $this->advertised_on_own_website;
    }


    /**
     * @param bool $advertised_on_own_website
     * @return $this
     */
    public function setAdvertisedOnOwnWebsite(bool $advertised_on_own_website): self
    {
        $this->advertised_on_own_website = (int)$advertised_on_own_website;
        return $this;
    }

    /**
     * @return int
     */
    public function getBerths(): int
    {
        return $this->berths;
    }

    /**
     * @param int $berth
     */
    public function setBerths(int $berths): self
    {
        $this->berths = $berths;
        return $this;
    }

    /**
     * @return int
     */
    public function getAxles(): int
    {
        return $this->axles;
    }


    /**
     * @param int $axles
     * @return $this
     */
    public function setAxles(int $axles): self
    {
        $this->axles = $axles;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayoutType(): string
    {
        return $this->layout_type;
    }

    /**
     * @param string $layout_type
     * @return $this
     */
    public function setLayoutType(string $layout_type): self
    {
        $this->layout_type = $layout_type;
        return $this;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }


    /**
     * @param float $width
     * @return $this
     */
    public function setWidth(float $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }


    /**
     * @param float $length
     * @return $this
     */
    public function setLength(float $length): self
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }


    /**
     * @param float $height
     * @return $this
     */
    public function setHeight(float $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getKimberleyUnitId(): int
    {
        return $this->kimberley_unit_id;
    }


    /**
     * @param int $kimberley_unit_id
     * @return $this
     */
    public function setKimberleyUnitId(int $kimberley_unit_id): self
    {
        $this->kimberley_unit_id = $kimberley_unit_id;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getKimberleyDateUpdated(): \DateTime
    {
        return $this->kimberley_date_updated;
    }

    /**
     * @param \DateTime $kimberley_date_updated
     * @return $this
     */
    public function setKimberleyDateUpdated(\DateTime $kimberley_date_updated): self
    {
        $this->kimberley_date_updated = $kimberley_date_updated;
        return $this;
    }


    

}
