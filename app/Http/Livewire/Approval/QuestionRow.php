<?php

namespace App\Http\Livewire\Approval;

use Illuminate\Support\Collection;
use Livewire\Component;

class QuestionRow extends Component
{
    /**
     * @var Collection $questions
     */

    public $questions;
    public $approvalLevel;

    protected $listeners = ['AddQuestion' => 'addNewQuestion'];


    function mount()
    {
        $this->questions = collect();
    }

    public function addNewQuestion()
    {
        $this->questions->push(['description' => '']);
    }

    public function render()
    {
        return view('livewire.approval.questions');
    }
}
