<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\CustomField;
use App\Http\Resources\TaskResource;
use App\Jobs\ApplyBusinessRules;
use App\Jobs\ApplySLA;
use App\Jobs\NewTaskJob;
use App\Mail\NewTaskMail;
use App\ReplyTemplate;
use App\Task;
use App\Ticket;
use App\TicketLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{

    public function index($ticket)
    {

        return Ticket::where('request_id', $ticket)
            ->where('type', config('types.task'))->get()->map(function ($task) {
                return $task->taskJson();
            });

    }

    public function create()
    {

    }

    public function store(Request $request)
    {

        $this->validate($request, ['task.subject' => 'required', 'task.category_id' => 'required']);

        Ticket::flushEventListeners();

        $requestTask = $request->get('task');
        $task = Ticket::create([
            'subject' => $requestTask['subject'],
            'description' => $requestTask['description'] ?? '',
            'type' => Ticket::TASK_TYPE,
            'request_id' => $requestTask['ticket_id'],
            'requester_id' => \Auth::id(),
            'creator_id' => \Auth::id(),
            'status_id' => 1,
            'category_id' => $requestTask['category_id'],
            'subcategory_id' => $requestTask['subcategory_id'],
            'item_id' => $requestTask['item_id'],
            'group_id' => $requestTask['group'],
            'technician_id' => $requestTask['technician_id'],
        ]);

        if (!empty($request->attachments)) {
            Attachment::uploadFiles(Attachment::TASK_TYPE, $task->id);
        }

        $items = json_decode($request->input('task.fields'), true);

        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $field = CustomField::find($key);

                if ($field && $field->required) {
                    $validation['cf.' . $key] = 'required';
                    $messages['cf.' . $key . '.required'] = "The field ( {$field->name} ) is required";
                }

            }

            foreach ($items as $key => $item) {
                if ($item) {
                    $field = CustomField::find($key)->name ?? '';

                    $task->fields()->create(['name' => $field, 'value' => $item]);
                }
            }
        }

        dispatch(new ApplyBusinessRules($task));

        if ($request['technician'] || $task->technician_id) {
            \Mail::queue(new NewTaskMail($task));
        }
        $task = TaskResource::make($task);
        return response()->json($task);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
    }


    public function edit(Ticket $task)
    {
        if (can('modify', $task)) {
            return view('ticket.task.edit', compact('task'));
        }

        return \Redirect::route('ticket.index');
    }


    public function update(Request $request, Ticket $ticket)
    {
        $this->validate($request, ['subject' => 'required', 'category_id' => 'required',
            'technician_id' => 'required', 'cf.*' => 'required'], ['cf.*' => 'Ticket Fields Required']);

        if (can('modify', $ticket)) {

            $ticket->fill([
                'subject' => $request['subject'],
                'description' => $request['description'],
                'group_id' => $request['group_id'],
                'category_id' => $request['category_id'],
                'subcategory_id' => $request['subcategory_id'],
                'technician_id' => $request['technician_id'],
                'item_id' => $request['item_id']
            ]);

            if (isset($ticket->getDirty()['technician_id']) && $ticket->getDirty()['technician_id'] != $ticket->getOriginal()['technician_id']) {
                \Mail::send(new NewTaskMail($ticket));
            }

            $ticket->save();

            if (!empty($request->attachments)) {
                Attachment::uploadFiles(Attachment::TASK_TYPE, $ticket->id);
            }

            $fields = $request->get('cf');

            if (!empty($fields)) {
                foreach ($fields as $key => $item) {
                    $field = CustomField::find($key);
                    if ($field && $field->required) {
                        $validation['cf.' . $key] = 'required';
                        $messages['cf.' . $key . '.required'] = "The field ( {$field->name} ) is required";
                    }
                }

                $ticket->fields()->delete();

                foreach ($fields as $key => $item) {
                    if ($item) {
                        $field = CustomField::find($key)->name ?? '';
                        $ticket->fields()->create(['name' => $field, 'value' => $item]);
                    }
                }
            }

        }


        flash(t('Task Info'), t('Task updated successfully'), 'success');
        return \Redirect::route('ticket.show', $ticket);
    }


    public function destroy($ticket, $task)
    {
        $task = Ticket::find($task);

        if (can('task_destroy', $task)) {
            $task->delete();
            flash(t('Task Info'), t('Task deleted successfully'), 'success');
        }
    }

}
