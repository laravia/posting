<?php

namespace Laravia\Posting\App\Models;

use Laravia\Heart\App\Models\Model;
use Orchid\Screen\AsSource;
use Spatie\Tags\HasTags;

class Posting extends Model
{
    use AsSource, HasTags;

    protected $dates = [
        'onlineFrom',
        'onlineTo',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'body',
        'title',
        'onlineFrom',
        'onlineTo',
        'created_at',
        'updated_at',
        'user_id',
        'project',
        'site',
        'element',
        'active',
    ];
}
