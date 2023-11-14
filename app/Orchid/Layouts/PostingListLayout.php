<?php

namespace Laravia\Posting\App\Orchid\Layouts;

use Laravia\Heart\App\Laravia;
use Laravia\Posting\App\Models\Posting;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Color;

class PostingListLayout extends Table
{
    public $target = 'postings';

    public function columns(): array
    {
        return [

            TD::make('id', 'ID')->filter(Input::make())->sort()->cantHide(),
            TD::make('title', 'Title')->filter(Input::make())->sort()->render(function ($posting) {
                return Link::make(substr($posting->title, 0, 35) . '...')->route('laravia.posting.edit', $posting);
            }),
            TD::make('created_at', 'Created')->filter(Input::make())->sort()->render(function ($posting) {
                return $posting->created_at;
            }),
            TD::make('onlineFrom', 'Online from')->filter(Input::make())->sort()->render(function ($posting) {
                return $posting->onlineFrom;
            }),
            TD::make('onlineTo', 'Online to')->filter(Input::make())->sort()->render(function ($posting) {
                return $posting->onlineTo;
            }),
            TD::make('active', 'Active')->filter(Input::make())->sort()->render(function ($posting) {
                $type = ($posting->active) ? Color::SUCCESS : Color::DANGER;
                return Link::make(($posting->active) ? __('Yes') : __('No'))
                    ->type($type)
                    ->turbo(false)
                    ->route('laravia.posting.edit', $posting);
            }),
            TD::make('language', 'Language')->filter(Input::make())->sort()->render(function ($posting) {
                return $posting->language;
            }),
            TD::make('tags', 'Tags')->render(function ($posting) {
                $tags = [];
                foreach ($posting->tags as $tag) {
                    $tags[] = $tag->name = $tag->getTranslation('name', $posting->language);
                }
                return implode(', ', $tags);
            }),

            TD::make('language', 'Language')->filter(Input::make())->sort()->render(function ($posting) {
                return data_get(Laravia::getDataFromConfigByKey('languages'), $posting->language);
            }),

            TD::make('type', 'Type')->filter(Input::make())->sort()->render(function ($posting) {
                return $posting->type;
            }),

            TD::make('project', 'Project')->filter(Input::make())->sort()->render(function ($posting) {
                return $posting->project;
            })->canSee(count(Laravia::getDataFromConfigByKey('projects'))),

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
