<?php

namespace App\Http\Livewire\Approval;

use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Livewire\Component;

class Index extends Component
{
    public $ticket;
    /**
     * @var Collection $approvalLevels
     */
    public $approvalLevels;
    public $approvals;
    public $users;
    protected $listeners = ['remove_level' => 'removeRow'];

    function mount()
    {

        $this->approvalLevels = collect();
        $this->users = User::where('email', '<>', null)->orderBy('name')->get();
        $this->addStages();
    }

    function addStages()
    {
        $approval = ['approver_id' => 0, 'stage' => 1, 'new_stage' => false, 'description' => collect(), 'questions' => [], 'attachments' => []];
        $this->approvalLevels->push($approval);
    }

    public function addQuestion($index)
    {

    }


    function removeRow($index)
    {
        $this->approvalLevels->pull($index);
        return $this->render();
    }


    function removeQuestion($lIndex, $qIndex)
    {

        $level = $this->approvalLevels->get($lIndex);
        array_pull($level['questions'], $qIndex);
    }

    public function render()
    {
        $approvalLevels = $this->approvalLevels;
        $count = $approvalLevels->count();
        return view('livewire.approval.index', compact('approvalLevels','count'));
    }
}
