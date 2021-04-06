<?php

namespace App\Http\Controllers\API;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Ticket;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    function index(Ticket $ticket)
    {
        $list = new \App\Http\Controllers\ListController();
        $task_categories = $list->category(\App\KModel::TASK_TYPE);

        return [
            'tasks' => $ticket->tasks()->get()->map(function ($task){
                return TaskResource::make($task);
            }),
            'task_create' => can('task_create', $ticket),
            'templates' => auth()->user()->reply_templates,
            'is_support' => auth()->user()->isSupport(),
            'groups' => Group::active()->technician()->tasks()->get(),
            'categories' => $task_categories,
        ];
    }

    function store(Request $request){

    }

    public function destroy($task)
    {
        $task = Ticket::find($task);

        if (can('task_destroy', $task)) {
            $task->delete();
//            flash(t('Task Info'), t('Task deleted successfully'), 'success');
        }
    }
}
