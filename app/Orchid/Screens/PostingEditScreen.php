<?php

namespace Laravia\Posting\App\Orchid\Screens;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravia\Heart\App\Laravia;
use Laravia\Posting\App\Models\Posting as ModelsPosting;
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

    public function layoutPosting()
    {
        return
            [
                Layout::split([
                    Layout::rows([
                        Input::make('posting.title')
                            ->title('Title')
                            ->placeholder('Title')
                            ->required(),
                        TextArea::make('posting.body')
                            ->title('Body')
                            ->rows(35)
                            ->required(),
                    ]),
                    Layout::rows([

                        Input::make('id')
                            ->type('hidden')
                            ->value($this->posting->id)
                            ->hidden(),
                        Select::make('posting.tags')
                            ->fromQuery(Tag::where('type', '=', 'posting'), 'name')
                            ->multiple()
                            ->allowAdd()
                            ->title('tags')
                            ->placeholder('choose or add tags'),
                        Select::make('posting.language')
                            ->title('Language Key')
                            ->options(Laravia::getDataFromConfigByKey('languages'))
                            ->placeholder('Language'),
                        Select::make('posting.type')
                            ->title('Type')
                            ->empty(__('Select or add a type'))
                            ->options(Laravia::getArrayWithDistinctFieldDataFromClassByKey(ModelsPosting::class, 'type'))
                            ->allowAdd()
                            ->placeholder('Site'),
                        Select::make('posting.site')
                            ->title('Site')
                            ->empty(__('Select or add a site'))
                            ->options(Laravia::getArrayWithDistinctFieldDataFromClassByKey(ModelsPosting::class, 'site'))
                            ->allowAdd()
                            ->placeholder('Site'),
                        Select::make('posting.element')
                            ->title('Element')
                            ->empty(__('Select or add a element'))
                            ->options(Laravia::getArrayWithDistinctFieldDataFromClassByKey(ModelsPosting::class, 'element'))
                            ->allowAdd()
                            ->placeholder('Element'),
                        CheckBox::make('posting.active')
                            ->title('Active')
                            ->placeholder('Active')
                            ->value(true)
                            ->style('margin-bottom:1.25em;'),
                        Select::make('posting.project')
                            ->title('Project')
                            ->options(Laravia::getDataFromConfigByKey('projects'))
                            ->placeholder('Project')
                            ->value(data_get(request()->all(), 'project'))
                    ])->canSee(sizeof(Laravia::getDataFromConfigByKey('projects'))),
                ])->ratio('50/50'),


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
                ])
            ];
    }

    public function layout(): array
    {
        return $this->layoutPosting();
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
        $this->posting->saveTags(data_get($posting, 'tags'), 'posting', data_get($posting, 'language'));

        Alert::info($text);

        return redirect(config('platform.prefix') . '/postings?filter[project]=' . $this->posting->project);
    }

    public function remove()
    {
        $this->posting->delete();

        Alert::info('You have successfully deleted the posting.');

        return redirect()->route('laravia.posting.list');
    }
}
