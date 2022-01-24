<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FormSubmission extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * @var array
     */
    protected $fillable = [
        'submission_data',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'submission_data' => 'array',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file-uploads');
    }

    public function getSubmissionDataValue(string $fieldLabel)
    {
        return isset($this->submission_data[$fieldLabel])
            ? $this->submission_data[$fieldLabel]
            : null;
    }
}
