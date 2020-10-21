<?php

namespace App\Http\Livewire\Approval;

use Livewire\Component;

class ApprovalItem extends Component
{

    public $level;
    public $users;
    public $index;
    public $count;

    function mount()
    {
        array_push($this->level['questions'], '', '', '');
    }

    public function addQuestion()
    {
//        $level = $this->approvalLevels->get($index);
        array_push($this->level['questions'], '');
//        $this->approvalLevels->put($index, $level);
    }

    public function render()
    {
        return view('livewire.approval.approval-item', ['count' => $this->count]);
    }
}
