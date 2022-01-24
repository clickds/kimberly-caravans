<?php

namespace App\Models;

use App\Presenters\FieldPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use McCool\LaravelAutoPresenter\HasPresenter;
use UnexpectedValueException;

class Field extends Model implements HasPresenter
{
    /**
     * @var array $fillable
     */
    protected $fillable = [
        'crm_field_name',
        'input_name',
        'label',
        'name',
        'options',
        'position',
        'required',
        'type',
        'width',
    ];

    /**
     * @var array $attributes
     */
    protected $attributes = [
        'position' => 0,
        'width' => self::WIDTH_FULL,
    ];

    /**
     * @var array $casts
     */
    protected $casts = [
        'options' => 'array',
        'required' => 'boolean',
    ];

    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_EMAIL = 'email';
    public const TYPE_MULTIPLE_CHECKBOXES = 'multiple-checkboxes';
    public const TYPE_RADIO_BUTTONS = 'radio-buttons';
    public const TYPE_SELECT = 'select';
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_CAPTCHA = 'captcha';
    public const TYPE_SUBMIT = 'submit';
    public const TYPE_DEALER_SELECT = 'dealer-select';
    public const TYPE_DEALER_CHECKBOXES = 'dealer-checkboxes';
    public const TYPE_BUSINESS_AREA_SELECT = 'business-area-select';
    public const TYPE_FILE_UPLOAD = 'file-upload';

    public const TYPES_REQUIRING_OPTIONS = [
        self::TYPE_MULTIPLE_CHECKBOXES,
        self::TYPE_RADIO_BUTTONS,
        self::TYPE_SELECT,
    ];

    public const TYPES = [
        self::TYPE_CHECKBOX => 'Checkbox',
        self::TYPE_EMAIL => 'Email',
        self::TYPE_MULTIPLE_CHECKBOXES => 'Multiple Checkboxes',
        self::TYPE_RADIO_BUTTONS => 'Radio Buttons',
        self::TYPE_SELECT => 'Select',
        self::TYPE_TEXT => 'Text',
        self::TYPE_TEXTAREA => 'Textarea',
        self::TYPE_CAPTCHA => 'Captcha',
        self::TYPE_SUBMIT => 'Submit',
        self::TYPE_DEALER_SELECT => 'Dealer Select',
        self::TYPE_DEALER_CHECKBOXES => 'Dealer Checkboxes',
        self::TYPE_BUSINESS_AREA_SELECT => 'Business Area Select',
        self::TYPE_FILE_UPLOAD => 'File Upload',
    ];

    public const WIDTH_FULL = 'Full';
    public const WIDTH_HALF = 'Half';
    public const WIDTH_THIRD = 'Third';
    public const WIDTH_TWO_THIRDS = 'Two Thirds';

    public const WIDTHS = [
        self::WIDTH_FULL,
        self::WIDTH_HALF,
        self::WIDTH_THIRD,
        self::WIDTH_TWO_THIRDS,
    ];

    public const CRM_FIELD_EMAIL = 'EmailAddress';
    public const CRM_FIELD_NAME = 'Name';
    public const CRM_FIELD_TITLE = 'Title';
    public const CRM_FIELD_ADDRESS_1 = 'Address1';
    public const CRM_FIELD_ADDRESS_2 = 'Address2';
    public const CRM_FIELD_CITY = 'Town/City';
    public const CRM_FIELD_COUNTY = 'County';
    public const CRM_FIELD_POSTCODE = 'Postcode';
    public const CRM_FIELD_HOME_PHONE = 'Home Telephone';
    public const CRM_FIELD_MOBILE = 'Mobile Telephone';
    public const CRM_FIELD_CONTACT_PREFERENCES = 'Contact Preferences';
    public const CRM_FIELD_RELEVANT_INFORMATION = 'Relevant Information';
    public const CRM_FIELD_PREFERRED_DEALER = 'Preferred Dealer';
    public const CRM_FIELD_PREFERRED_DEALERS = 'Preferred Dealers';
    public const CRM_FIELD_BUSINESS_AREA = 'Business Area';
    public const CRM_FIELD_FILE_UPLOAD = 'File Upload';

    public const CRM_FIELD_NAMES = [
        self::CRM_FIELD_EMAIL,
        self::CRM_FIELD_NAME,
        self::CRM_FIELD_TITLE,
        self::CRM_FIELD_ADDRESS_1,
        self::CRM_FIELD_ADDRESS_2,
        self::CRM_FIELD_CITY,
        self::CRM_FIELD_COUNTY,
        self::CRM_FIELD_POSTCODE,
        self::CRM_FIELD_HOME_PHONE,
        self::CRM_FIELD_MOBILE,
        self::CRM_FIELD_CONTACT_PREFERENCES,
        self::CRM_FIELD_RELEVANT_INFORMATION,
        self::CRM_FIELD_PREFERRED_DEALER,
        self::CRM_FIELD_PREFERRED_DEALERS,
        self::CRM_FIELD_BUSINESS_AREA,
        self::CRM_FIELD_FILE_UPLOAD,
    ];

    public function fieldset(): Relation
    {
        return $this->belongsTo(Fieldset::class);
    }

    /**
     * @param mixed $value
     */
    public function setTypeAttribute($value): void
    {
        $allowedTypes = array_keys(self::TYPES);
        if (!in_array($value, $allowedTypes)) {
            throw new UnexpectedValueException('Invalid type');
        }
        $this->attributes['type'] = $value;
    }

    public function humanisedType(): string
    {
        return self::TYPES[$this->type];
    }

    /**
     * Whether the field requires options
     */
    public function requiresOptions(): bool
    {
        return self::typeRequiresOptions($this->type);
    }

    public static function typeRequiresOptions(string $type): bool
    {
        return in_array($type, self::TYPES_REQUIRING_OPTIONS);
    }

    public function getPresenterClass(): string
    {
        return FieldPresenter::class;
    }
}
