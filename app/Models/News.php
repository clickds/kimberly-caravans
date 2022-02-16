<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JetBrains\PhpStorm\Pure;

/**
 * Class News
 * @package App\Models
 * @property int $id
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property boolean $published
 * @property int $author_id
 */
class News extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'published' => 'boolean'
    ];

    /**
     * @var string
     */
    protected $table = 'news';


    /**
     * @return BelongsTo
     */
    public function author() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'author_id');
    }

    /**
     * @param int $characters
     * @param string $openWrap
     * @param string $closeWrap
     * @return string
     */
    public function buildExcerpt(int $characters = 50, string $openWrap = '<p>', string $closeWrap = '</p>') : string
    {
        return $openWrap . substr(strip_tags($this->content), 0, $characters) . $closeWrap;
    }
}
