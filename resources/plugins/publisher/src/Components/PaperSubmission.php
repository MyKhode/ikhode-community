<?php

namespace Wave\Plugins\Publisher\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Filament\Forms; // For future rich editor integration
use Livewire\WithFileUploads;
use Wave\Plugins\Publisher\Models\Paper;
use Wave\Plugins\Publisher\Models\PaperResearcher;

class PaperSubmission extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public string $title = '';
    public string $abstract = '';
    public string $markdown_content = '';
    public array $researchers = [['name' => '', 'affiliation' => '']];

    public function addResearcher()
    {
        $this->researchers[] = ['name' => '', 'affiliation' => ''];
    }

    public function removeResearcher($index)
    {
        unset($this->researchers[$index]);
        $this->researchers = array_values($this->researchers);
    }

    public function submit()
    {
        $validated = Validator::make(
            [
                'title' => $this->title,
                'abstract' => $this->abstract,
                'markdown_content' => $this->markdown_content,
                'researchers' => $this->researchers,
            ],
            [
                'title' => 'required|min:6',
                'abstract' => 'required|min:10',
                'markdown_content' => 'required|min:20',
                'researchers.*.name' => 'required',
            ]
        )->validate();

        $paper = Paper::create([
            'title' => $this->title,
            'abstract' => $this->abstract,
            'markdown_content' => $this->markdown_content,
            'user_id' => Auth::id(),
        ]);

        foreach ($this->researchers as $researcher) {
            PaperResearcher::create([
                'paper_id' => $paper->id,
                'name' => $researcher['name'],
                'affiliation' => $researcher['affiliation'],
            ]);
        }

        session()->flash('success', 'Paper submitted successfully!');
        return redirect()->route('papers.submit');
    }

    public function render()
    {
        $layout = auth()->guest() 
            ? 'theme::components.layouts.marketing' 
            : 'theme::components.layouts.app';

        return view('publisher::livewire.submission')->layout($layout);
    }
}
