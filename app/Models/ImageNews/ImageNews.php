<?php

declare(strict_types=1);

namespace App\Models\ImageNews;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ImageNews
 * @package App\Models\ImageNews
 */
class ImageNews extends Model
{
    /**
     * @var string
     */
    protected $table = 'image_news';

    /**
     * @var string[]
     */
    protected $fillable = [
        'news_id',
        'image',
        'description',
        'active',
        'created_at'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public array $rules = [
        'news_id' => 'required|numeric',
        'image' => 'required',
        'description' => 'required|min:10|max:255'
    ];
}
