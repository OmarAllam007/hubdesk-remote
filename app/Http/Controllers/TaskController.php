<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\CustomField;
use App\Jobs\ApplySLA;
use App\Jobs\NewTaskJob;
use App\Mail\NewTaskMail;
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, ['subject' => 'required', 'category' => 'required']);

        if ($request['technician']) {
            Ticket::flushEventListeners();
        }

        $task = Ticket::create([
            'subject' => $request['subject'],
            'description' => $request['description'] ?? '',
            'type' => config('types.task'),
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



        if(count($request->attachments)){
            Attachment::uploadFiles(Attachment::TICKET_TYPE, $task->id);
        }

        if(count($request->cf)){
            foreach ($request->get('cf', []) as $key=>$item) {

                $field = CustomField::find($key);
//            dd($field->name);
                if($field && $field->required){
                    $validation['cf.'.$key] = 'required';
                    $messages['cf.'.$key.'.required'] = "The field ( {$field->name} ) is required";
                }

            }

            foreach ($request->get('cf', []) as $key=>$item){
                if($item) {
                    $field = CustomField::find($key)->name ?? '';

                    $task->fields()->create(['name' => $field, 'value' => $item]);
                }
            }
        }



        /** @var Ticket $task */

//        TicketLog::addNewTask($task);

        if ($request['technician']) {
            dispatch(new NewTaskJob($task));
        }


        return response()->json($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
    }

    /**
     * Show the form for editing the specified resource
     *
     * @param  \App\Task $task
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
            'technician_id' => 'required']);
        if (can('modify', $ticket)) {
            $ticket->fill(['subject' => $request['subject'],
                'description' => $request['description'],
                'category_id' => $request['category_id'],
                'subcategory_id' => $request['subcategory_id'],
                'technician_id' => $request['technician_id'],
                'item_id' => $request['item_id']]);

            if (isset($ticket->getDirty()['technician_id']) && $ticket->getDirty()['technician_id']!= $ticket->getOriginal()['technician_id']) {
                Mail::send(new NewTaskMail($ticket));
            }
            $ticket->save();
        }

        flash(t('Task Info'), t('Task updated successfully'),'success');
        return \Redirect::route('ticket.show', $ticket);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($ticket, $task)
    {
        $task = Ticket::find($task);
        if (can('delete', $task)) {
            $task->delete();
            flash(t('Task Info'),t('Task deleted successfully'), 'success');
        }
    }

}
