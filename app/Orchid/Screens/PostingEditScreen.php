<?php

namespace Laravia\Posting\App\Orchid\Screens;

use Illuminate\Http\Request;
use Laravia\Posting\App\Models\Posting as ModelsPosting;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class PostingEditScreen extends Screen
{
    public $posting;

    public function query(ModelsPosting $posting): array
    {
        return [
            'posting' => $posting
        ];
    }

    public function name(): ?string
    {
        return $this->posting->exists ? 'Edit posting' : 'Creating a new posting';
    }

    public function description(): ?string
    {
        return "Postings";
    }

    public function commandBar(): array
    {
        return [
            Button::make('Create posting')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->posting->exists),

            Button::make('Update')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee($this->posting->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->posting->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('posting.title')
                    ->title('Title')
                    ->placeholder('Title')
                    ->required(),
                Input::make('posting.tags')
                    ->title('tags')
                    ->placeholder('tags (separated by commas)')
                    ->help('Select tags'),

                SimpleMDE::make('posting.body')
                    ->title('Body')
                    ->placeholder('markdown')
                    ->required(),
            ]),

            Layout::columns([
                Layout::rows([
                    Select::make('posting.project')
                        ->options([
                            '1' => 'Project 1',
                            '2' => 'Project 2',
                            '3' => 'Project 3',
                        ])
                        ->title('Project')
                        ->required()
                        ->placeholder('Project'),
                ]),
                Layout::rows([
                    Input::make('posting.site')
                        ->title('Site')
                        ->placeholder('Site'),
                ]),
                Layout::rows([
                    Input::make('posting.element')
                        ->title('Element')
                        ->placeholder('Element'),
                ]),

                Layout::rows([
                    CheckBox::make('posting.active')
                        ->title('Active')
                        ->placeholder('Active')
                        ->value(true),
                ]),
            ]),


            Layout::columns([
                Layout::rows([
                    DateTimer::make('posting.onlineFrom')
                        ->title('Online from')
                        ->enableTime()
                        ->format24hr(),
                    DateTimer::make('posting.onlineTo')
                        ->title('Online to')
                        ->enableTime()
                        ->format24hr(),
                ]),
                Layout::rows([
                    DateTimer::make('posting.created_at')
                        ->title('Created')
                        ->enableTime()
                        ->format24hr(),
                    DateTimer::make('posting.updated_at')
                        ->title('Updated')
                        ->enableTime()
                        ->format24hr(),
                ]),
            ]),
        ];
    }

    public function createOrUpdate(Request $request)
    {

        $posting = $request->get('posting');
        $posting['user_id'] = $request->user()->id;
        $this->posting->fill($posting)->save();

        Alert::info('You have successfully created a posting.');

        return redirect()->route('laravia.posting.list');
    }

    public function remove()
    {
        $this->posting->delete();

        Alert::info('You have successfully deleted the posting.');

        return redirect()->route('laravia.posting.list');
    }
}
