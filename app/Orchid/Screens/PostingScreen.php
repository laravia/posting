<?php

namespace Laravia\Posting\App\Orchid\Screens;

use Illuminate\Http\Request;
use Laravia\Posting\App\Models\Posting as ModelsPosting;
use Laravia\Posting\App\Orchid\Layouts\PostingListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class PostingScreen extends Screen
{

    public function query(): iterable
    {
        return [
            'metrics' => [
                'postings' => ['all' => ModelsPosting::count()],
            ],

            'postings' => ModelsPosting::orderByDesc('id')->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Posting Screen';
    }

    public function description(): ?string
    {
        return 'Postings of Laravia';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Create new posting')
                ->icon('pencil')
                ->route('laravia.posting.edit')
        ];
    }

    public function layout(): iterable
    {
        return [
            PostingListLayout::class
        ];
    }

    public function remove(Request $request): void
    {
        ModelsPosting::findOrFail($request->get('id'))->delete();

        Alert::info('You have successfully deleted the posting.');
    }
}
