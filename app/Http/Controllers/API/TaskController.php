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
            'tasks' => TaskResource::collection($ticket->tasks),
            'task_create' => can('task_create', $ticket),
            'templates' => auth()->user()->reply_templates,
            'is_support' => auth()->user()->isSupport(),
            'groups' => Group::active()->technician()->tasks()->get(),
            'categories' => $task_categories,
        ];
    }
}
