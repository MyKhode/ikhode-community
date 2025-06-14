<?php

namespace Wave\Plugins\Discussions\Components;

use Wave\Plugins\Discussions\Models\Models;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\MarkdownEditor;
use Livewire\Component;
use Livewire\WithPagination;
use Validator;

class DiscussionPosts extends Component implements HasForms
{
    use InteractsWithForms, WithPagination;

    public ?array $data = [];
    public $discussion;
    public $discussion_id;
    public $editingPostId = null;
    public $editedContent;
    public $perPage = 5;

    public $listeners = ['postAdded' => '$refresh'];

    public function updatingPage() {
        $this->resetPage(); // resets pagination when other input changes (optional safety)
    }

    public function mount($discussion)
    {
        $this->discussion = $discussion;
        $this->discussion_id = $discussion->id;
        $this->form->fill();
    }


    public function loadMore()
    {
        $this->perPage += 5;
    }

    public function delete($id)
    {
        $post = Models::post()->find($id);
        if (auth()->user()->id !== $post->user_id) {
            Notification::make()
                ->title(trans('discussions::alert.danger.reason.destroy'))
                ->success()
                ->send();
            return;
        }
        $post->delete();
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
                $editor->label(false)->placeholder(trans('discussions::messages.editor.content'))
            ])
            ->statePath('data');
    }

    public function edit($id)
    {
        $post = Models::post()->find($id);
        if (auth()->user()->id !== $post->user_id) {
            Notification::make()
                ->title(trans('discussions::alert.danger.reason.update_post'))
                ->warning()
                ->send();
            return;
        }
        $this->editingPostId = $id;
        $this->editedContent = $post->content;

        $this->form->fill([
            'content' => $this->editedContent
        ]);
    }

    public function cancelEdit()
    {
        $this->editingPostId = null;
        $this->editedContent = null;
    }

    public function update($id)
    {
        $state = $this->form->getState();
        $this->editedContent = $state['content'];

        $post = Models::post()->find($id);

        if (!$post || auth()->user()->id !== $post->user_id) {
            Notification::make()
                ->title(trans('discussions::alert.danger.reason.update_post'))
                ->warning()
                ->send();
            return;
        }

        $validator = Validator::make(['editedContent' => $this->editedContent], [
            'editedContent' => 'required',
        ]);

        if ($validator->fails()) {
            Notification::make()
                ->title('Validation error')
                ->danger()
                ->body($validator->errors()->first())
                ->send();
            return;
        }

        $post->update(['content' => $this->editedContent]);

        $this->editingPostId = null;
    }

    public function render()
    {
        $posts = Models::post()
            ->where('discussion_id', $this->discussion->id)
            ->orderBy('created_at', 'asc')
            ->paginate($this->perPage);

        return view('discussions::livewire.discussion-posts', compact('posts'));
    }
}
