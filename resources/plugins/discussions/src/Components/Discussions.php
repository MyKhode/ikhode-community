<?php

namespace Wave\Plugins\Discussions\Components;

use Livewire\Component;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Validator;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Wave\Plugins\Discussions\Models\Discussion;
use Wave\Plugins\Discussions\Events\NewDiscussionCreated;
use Illuminate\Support\Facades\Http;


class Discussions extends Component implements HasForms
{
    use InteractsWithForms;
    
    public ?array $data = [];
    public $title;
    public $content;

    #[Url]
    public $search;

    #[Url]
    public $sort = 'desc';
    public $category_slug;

    #[Url]
    public $category;
    public $loadMore = 10;

    public function mount($category = null)
    {
        $this->loadMore = config('discussions.load_more.posts');
        $this->category = $category;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $editor = match(config('discussions.editor')){
            'textarea' => Textarea::make('content')->rows(8),
            'richeditor' => RichEditor::make('content'),
            'markdown' => MarkdownEditor::make('content')
        };

        return $form
            ->schema([
                $editor
                    ->label(false)
                    ->placeholder(trans('discussions::messages.editor.content'))
            ])
            ->statePath('data');
    }

    public function loadMoreDiscussions()
    {
        $this->loadMore = $this->loadMore + config('discussions.load_more.posts');
    }

    public function slugValidation($slug)
    {
        $slug = Str::slug($slug);
        $count = Discussion::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }
        return $slug;
    }

    public function createDiscussion()
    {
        $state = $this->form->getState();
        $this->content = $state['content'];

        $rules = [
            'title' => 'required|min:6',
            'content' => 'required|min:6',
        ];
        
        $validator = Validator::make($this->getDataForValidation($rules), $rules);

        if($validator->fails()) {
            Notification::make()
                ->title($validator->errors()->first())
                ->warning()
                ->send();
            return;
        }


        if ($this->checkTimeBetweenDiscussion() === false) {
            return;
        }

        $slug = $this->slugValidation($this->title);

        $discussion = Discussion::create([
            'title' => $this->title,
            'category_slug' => $this->category_slug,
            'content' => $this->content,
            'slug' => $slug,
            'user_id' => auth()->user()->id,
        ]);

      
        Http::post('https://discordapp.com/api/webhooks/1382297988545777737/RuSNMC-OAbQLjieGu2Y0DDWrPTo67x0JGe2LWLWLWPOhmcHbOM3hDl6SomGXZrNnlSO1', [
            'content' => '👋 Someone just dropped a new discussion on the  website!',
            'embeds' => [[
                'title' => 'New Discussion Created',
                'color' => 5814783,
                'fields' => [
                    [
                        'name' => 'Title', 'value' => $discussion->title, 'inline' => false,
                    ],
                    [
                        'name' => 'URL', 'value' => url('/discussion/' . $discussion->slug), 'inline' => false,
                    ],
                    [
                        'name' => 'content', 'value' => Str::limit(strip_tags($discussion->content), 100, '...'), 'inline' => false,    
                    ]
                ],
            ]],
            'username' => auth()->user()->name,
            'avatar_url' => auth()->user()->avatar() ?? 'https://avatars.githubusercontent.com/u/178536272?s=280&v=4', 
        ]);
        $this->title = '';
        $this->content = '';

        event(new NewDiscussionCreated($discussion));

        // clear the form
        $this->form->fill();

        $this->js("window.dispatchEvent(new CustomEvent('close-new-discussion', {}));");

        Notification::make()
            ->title(trans('discussions::text.titles.discussion') . ' created successfully.')
            ->success()
            ->send();
    }

    public function checkTimeBetweenDiscussion()
    {
        if (config('discussions.security.limit_time_between_posts') === true) {
            $lastDiscussion = Discussion::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
            if ($lastDiscussion != null) {
                $timeBetween = abs(now()->diffInMinutes($lastDiscussion->created_at));
                if ($timeBetween < config('discussions.security.time_between_posts')) {
                    Notification::make()
                        ->title(trans('discussions::alert.danger.reason.prevent_spam', ['minutes' => config('discussions.security.time_between_posts')]))
                        ->warning()
                        ->send();
                    return false;
                }
            }
        }
    }

    public function updateSortOrder($order)
    {
        if ($order != 'asc' && $order != 'desc') {
            return;
        }
        $this->sort = $order;
    }


    public function setCreateCategory($slug)
    {
        if (!array_key_exists($slug, config('discussions.categories'))) {
            $this->category_slug = null;
        }
        $this->category_slug = $slug;
    }

    public function setCategory($slug)
    {
        if (!array_key_exists($slug, config('discussions.categories'))) {
            $this->category = null;
        }
        $this->category = $slug;
    }

    public function performSearch()
    {
        $this->discussions = $this->getDiscussionsQuery()->paginate($this->loadMore);
    }

    protected function getDiscussionsQuery()
    {
        return Discussion::query()
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                return $query->where('category_slug', $this->category);
            })
            ->orderBy('created_at', $this->sort)
            ->with('subscribers');
    }

    public function render()
    {
        $discussions = $this->getDiscussionsQuery()->paginate($this->loadMore);

        $layout = (auth()->guest()) ? 'theme::components.layouts.marketing' : 'theme::components.layouts.app';

        return view('discussions::livewire.discussions', ['discussions' => $discussions])->layout($layout);
    }
}
