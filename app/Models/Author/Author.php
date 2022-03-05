<?php

declare(strict_types=1);

namespace App\Models\Author;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Author
 * @package App\Models\Author
 */
class Author extends Model
{
    /**
     * @var string
     */
    protected $table = 'authors';

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'gender',
        'active',
        'created_at'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'password'
    ];

    /**
     * @var bool 
     */
    public $timestamps = false;

    /**
     * @var array/string[]
     */
    public array $rules = [
        'name' => 'required|min:2|max:45|alpha',
        'lastname' => 'required|min:2|max:60|alpha',
        'email' => 'required|email|max:100|email:rfc,dns',
        'password' => 'required|between:6,12',
        'gender' => 'required|alpha|max:1'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function news()
    {
        return $this->hasMany(News::class);
    }
}
