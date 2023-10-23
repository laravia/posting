<?php

namespace Laravia\Posting\App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Laravia\Heart\App\Models\Model;
use Laravia\Tag\App\Traits\HasTags;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Screen\AsSource;

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
        'language',
        'type',
    ];
    protected $allowedFilters = [
        'id' => Where::class,
        'title' => Like::class,
        'active' => Where::class,
        'type' => Where::class,
        'created_at' => WhereDateStartEnd::class,
        'updated_at' => WhereDateStartEnd::class,
        'project' => Where::class,
        'language' => Where::class,
    ];
    protected $allowedSorts = [
        'id',
        'title',
        'active',
        'created_at',
        'updated_at',
        'project',
        'language',
        'type',
    ];

    public function scopeWhereIsOnline($query)
    {
        return $query
            ->where('active', true)

            ->where(function ($query) {
                $query->where('onlineFrom', '<', Carbon::now())->where('onlineTo', '>', Carbon::now());
                $query->orWhere(function ($query) {
                    $query->where('onlineFrom', null)->where('onlineTo', null);
                });
            })

            ->orWhere(function ($query) {
                $query->where('active', true);
                $query->where('onlineFrom', '<', Carbon::now())->where('onlineTo', null);
            })

            ->orWhere(function ($query) {
                $query->where('active', true);
                $query->where('onlineTo', '>', Carbon::now())->where('onlineFrom', null);
            });
    }

    public static function getSearchResultsIdFromCurrentProject(
        $searchPhrase,
        $project
    ): \Illuminate\Support\Collection {

        $contents = Posting::where('project', '=', $project)
            ->where(function (Builder $query) use ($searchPhrase) {
                return $query->where('body', 'like', '%' . $searchPhrase . '%')
                    ->orWhere('title', 'like', '%' . $searchPhrase . '%');
            })->pluck('id', 'id');

        $searchPhraseAsKey = preg_split("/[\;|\+|\,|\*]/i", $searchPhrase);
        $contentsFromTags = Posting::where('project', '=', $project)
            ->withAnyTags($searchPhraseAsKey)
            ->pluck('id', 'id');
        return $contents->merge($contentsFromTags);
    }

    public static function getCountFromProject($project)
    {
        return Posting::where('project', '=', $project)->count();
    }
}
