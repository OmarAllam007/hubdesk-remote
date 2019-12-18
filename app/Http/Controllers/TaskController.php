<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\CustomField;
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ticket)
    {

        return Ticket::where('request_id', $ticket)
            ->where('type', config('types.task'))->get()->map(function ($task) {
                return $task->taskJson();
            });

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['subject' => 'required', 'category' => 'required']);

        Ticket::flushEventListeners();

        if ($request->template) {
            $request['description'] = ReplyTemplate::find($request->template)->description;
        }

        $task = Ticket::create([
            'subject' => $request['subject'],
            'description' => $request['description'] ?? '',
            'type' => Ticket::TASK_TYPE,
            'request_id' => $request['ticket_id'],
            'requester_id' => \Auth::id(),
            'creator_id' => \Auth::id(),
            'status_id' => 1,
            'category_id' => $request['category'],
            'subcategory_id' => $request['subcategory'],
            'item_id' => $request['item'],
            'group_id' => $request['group'],
            'technician_id' => $request['technician'],
        ]);

        if (!empty($request->attachments)) {
            Attachment::uploadFiles(Attachment::TASK_TYPE, $task->id);
        }

        $items = json_decode($request->input('cf'), true);

        if (!empty($items)) {
            foreach ($items as $key => $item) {

                $field = CustomField::find($key);
//            dd($field->name);
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


        /** @var Ticket $task */

//        TicketLog::addNewTask($task);

        if ($request['technician']) {
            Mail::send(new NewTaskMail($task));
//            dispatch(new NewTaskJob($task));
        }


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

    /**
     * Show the form for editing the specified resource
     *
     * @param \App\Task $task
     * @return \Illuminate\Http\Response
     */
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
            'technician_id' => 'required', 'cf.*' => 'required'],['cf.*'=>'Ticket Fields Required']);

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

            if(!empty($fields)){
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


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($ticket, $task)
    {
        $task = Ticket::find($task);

        if (can('task_destroy', $task)) {
            $task->delete();
            flash(t('Task Info'), t('Task deleted successfully'), 'success');
        }
    }

}
