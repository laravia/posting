<?php

namespace Laravia\Posting\App\Orchid\Screens;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravia\Heart\App\Laravia;
use Laravia\Posting\App\Models\Posting as ModelsPosting;
use Laravia\Tag\App\Tag as LaraviaTag;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Spatie\Tags\Tag;

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

                Input::make('id')
                    ->type('hidden')
                    ->value($this->posting->id)
                    ->hidden(),
                Input::make('posting.title')
                    ->title('Title')
                    ->placeholder('Title')
                    ->required(),
                Select::make('posting.tags')
                    ->fromQuery(Tag::where('type', '=', 'posting'), 'name')
                    ->multiple()
                    ->allowAdd()
                    ->title('tags')
                    ->placeholder('choose or add tags'),
                TextArea::make('posting.body')
                    ->title('Body')
                    ->rows(35)
                    ->required(),
            ]),


            Layout::columns([

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
                Layout::rows([
                    Select::make('posting.project')
                        ->title('Project')
                        ->options(Laravia::getDataFromConfigByKey('projects'))
                        ->placeholder('Project')
                ])->canSee(sizeof(Laravia::getDataFromConfigByKey('projects'))),
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
        $text = "";
        $posting = $request->get('posting');
        $posting['user_id'] = $request->user()->id;
        $posting['active'] = isset($posting['active']);

        if (empty($posting['updated_at'])) {
            $posting['updated_at'] = Carbon::now();
        }
        if (Laravia::isNewEntry()) {
            if (empty($posting['created_at'])) {
                $posting['created_at'] = Carbon::now();
            }
            $text = __('You have successfully created a posting.');
        } else {
            $text = __('You have successfully updated a posting.');
        }

        $this->posting->fill($posting)->save();
        $this->posting->syncTagsWithType(LaraviaTag::getSpatieTagsFromOrchidRequest($posting['tags']), 'posting');

        Alert::info($text);

        return redirect()->route('laravia.posting.list');
    }

    public function remove()
    {
        $this->posting->delete();

        Alert::info('You have successfully deleted the posting.');

        return redirect()->route('laravia.posting.list');
    }
}
