<?php

namespace Laravia\Posting\App\Orchid\Layouts;

use Laravia\Posting\App\Models\Posting;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;

class PostingListLayout extends Table
{
    public $target = 'postings';

    public function columns(): array
    {
        return [

            TD::make('id', 'ID')->sort()->cantHide(),
            TD::make('title', 'Title')->sort()->render(function ($posting) {
                return $posting->title;
            }),
            TD::make('created_at', 'Created')->sort()->render(function ($posting) {
                return $posting->created_at;
            }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Posting $posting) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Edit'))
                            ->route('laravia.posting.edit', $posting->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Once the posting is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                            ->method('remove', [
                                'id' => $posting->id,
                            ]),
                    ]))
        ];
    }
}
