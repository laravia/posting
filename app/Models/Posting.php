<?php

namespace Laravia\Posting\App\Models;

use Laravia\Heart\App\Models\Model;
use Orchid\Screen\AsSource;

class Posting extends Model
{
    use AsSource;

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
