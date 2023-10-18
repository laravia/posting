<?php

namespace Laravia\Posting\App\Models;

use Laravia\Heart\App\Models\Model;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Screen\AsSource;
use Spatie\Tags\HasTags;

class Posting extends Model
{
    use AsSource, HasTags, Filterable;

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
    protected $allowedFilters = [
        'id'         => Where::class,
        'title'       => Like::class,
        'active'      => Where::class,
        'created_at' => WhereDateStartEnd::class,
        'updated_at' => WhereDateStartEnd::class,
        'project' => Where::class,
    ];
    protected $allowedSorts = [
        'id',
        'title',
        'active',
        'created_at',
        'updated_at'
    ];
}
