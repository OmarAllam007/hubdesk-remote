<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\BusinessUnit;
use App\BusinessUnitRole;
use App\Category;
use App\CustomField;
use App\Helpers\Ticket\TicketViewScope;
use App\Http\Requests\NoteRequest;
use App\Http\Requests\ReassignRequest;
use App\Http\Requests\TicketReplyRequest;
use App\Http\Requests\TicketRequest;
use App\Http\Requests\TicketResolveRequest;
use App\Item;
use App\Jobs\ApplySLA;
use App\Jobs\NewNoteJob;
use App\Jobs\NewTicketJob;
use App\Jobs\TicketAssigned;
use App\Jobs\TicketReplyJob;
use App\Mail\TicketForwardJob;
use App\Subcategory;
use App\Ticket;
use App\TicketApproval;
use App\TicketField;
use App\TicketLog;
use App\TicketNote;
use App\TicketReply;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $ticketScope = $this->handleTicketsScope();
        $query = $ticketScope['query'];
        $scope = $ticketScope['scope'];
        $scopes = $ticketScope['scopes'];
        $tickets = $query->latest('id')->paginate();
        return view('ticket.index', compact('tickets', 'scopes', 'scope'));
    }

    public function create()
    {
        return view('ticket.create_ticket.index');
    }

    public function createTicket(BusinessUnit $business_unit, Category $category, Subcategory $subcategory, Item $item)
    {
        return view('ticket.create', compact('business_unit', 'category', 'subcategory', 'item'));
    }

    public function store(Request $request)
    {
        $validation = ['subject' => 'required', 'description' => 'required'];

        $messages = [];

        foreach ($request->get('cf', []) as $key => $item) {
            $field = CustomField::find($key);
            if ($field && $field->required) {
                $validation['cf.' . $key] = 'required';
                $messages['cf.' . $key . '.required'] = "The field ( {$field->name} ) is required";
            }
        }

        $this->validate($request, $validation, $messages);

        $ticket = new Ticket($request->all());

        $ticket->creator_id = $request->user()->id;

        if (!$request->get('requester_id')) {
            $ticket->requester_id = $request->user()->id;
        }

        $ticket->location_id = $ticket->requester->location_id;
        $ticket->business_unit_id = $ticket->requester->business_unit_id;
        $ticket->status_id = 1;

        $ticket->save();

        foreach ($request->get('cf', []) as $key => $item) {
            if ($item) {
                $field = CustomField::find($key)->name ?? '';

                if (is_array($item) && count($item) > 0) {
                    $item = implode(", ", $item);
                }
                $ticket->fields()->create(['name' => $field, 'value' => $item]);
            }
        }
//        $ticket->syncFields($request->get('cf', []));

        $this->dispatch(new NewTicketJob($ticket));

        flash(t('Ticket has been saved'), 'success');

        return \Redirect::route('ticket.show', $ticket);
    }

    public function show(Ticket $ticket)
    {
        return view('ticket.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        return view('ticket.edit', compact('ticket'));
    }

    public function update(Ticket $ticket, TicketRequest $request)
    {
        // Fires updated event in \App\Providers\TicketEventsProvider
//        $ticket->update($request->all());
//
//        flash('Ticket has been saved', 'success');

        return \Redirect::route('ticket.index');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        flash(t('Ticket has been deleted'), 'success');

        return \Redirect::route('ticket.index');
    }

    public function reply(Ticket $ticket, TicketReplyRequest $request)
    {
        if (isset($request->get("reply")["cc"])) {
            $this->validate($request, ['reply.cc.*' => 'email'], ['email' => 'Please enter valid emails']);
        }
        if (in_array($request->reply['status_id'], [7, 8, 9]) && $ticket->hasOpenTask()) {

            flash(t('Ticket has pending tasks'), 'error');
            return \Redirect::route('ticket.show', compact('ticket'));
        }

        $reply = new TicketReply($request->get('reply'));
        $reply->user_id = $request->user()->id;
        $reply->cc = $request->get("reply")["cc"] ?? null;
        // Fires creating event in \App\Providers\TicketReplyObserver


        // Fires creating event in \App\Providers\TicketReplyEventProvider
        $ticket->replies()->save($reply);
        flash(t('Reply has been added'), 'success');

        return \Redirect::route('ticket.show', compact('ticket'));
    }

    public function resolution(Ticket $ticket, TicketResolveRequest $request)
    {
        if ($ticket->hasOpenTask()) {
            alert()->flash(t('Pending Tasks'), 'error', [
                'text' => t('Ticket has pending tasks'),
                'timer' => 3000
            ]);

            return \Redirect::route('ticket.show', compact('ticket'));
        }

        $data = ['content' => $request->get('content'), 'status_id' => 7, 'user_id' => $request->user()->id];
        // Fires creating event in \App\Providers\TicketReplyEventProvider
        $reply = $ticket->replies()->create($data);

        //@todo: Calculate elapsed time
        $this->dispatch(new TicketReplyJob($reply));

        alert()->flash(t('Ticket Info'), 'success', [
            'text' => t('Ticket has been resolved'),
            'timer' => 3000
        ]);
        return $this->backSuccessResponse($request, '');
    }

    public function jump(Request $request)
    {
        $ticket = Ticket::find(intval($request->id)) ?? Ticket::where('sdp_id', intval($request->id))->first();

        if ($ticket && $ticket->hasDuplicatedTickets()) {
            $ticketScope = $this->handleTicketsScope();
            $query = $ticketScope['query'];
            $scope = $ticketScope['scope'];
            $scopes = $ticketScope['scopes'];

            $tickets = $query->where('request_id', $ticket->id)
                ->orWhere('id', $ticket->id)
                ->orWhere('sdp_id', $ticket->id)->paginate();


            return view('ticket.index', compact('tickets', 'scopes', 'scope'));
        }

        if ($ticket) {
            return \Redirect::route('ticket.show', $ticket->id);
        }

        alert()->flash(t('Ticket Info'), 'error', [
            'text' => t('Ticket not found'),
            'timer' => 3000
        ]);
        return \Redirect::route('ticket.index');
    }

    public function handleTicketsScope()
    {
        $scope = \Session::get('ticket.scope', 'my_pending');

        if (\Session::has('ticket.filter')) {
            $query = Ticket::scopedView('in_my_groups')->filter(session('ticket.filter'));
        } else {
            $query = Ticket::scopedView($scope);
        }

        $scopes = TicketViewScope::getScopes();

        return collect(['scope' => $scope, 'query' => $query, 'scopes' => $scopes]);
    }

    public function reassign(Ticket $ticket, ReassignRequest $request)
    {
        $current_technician = $ticket->technician_id;

        $ticket->update($request->only(['group_id', 'technician_id', 'category_id', 'subcategory_id', 'item_id']));

        if ($request->get('technician_id') != $current_technician) {
            $this->dispatch(new TicketAssigned($ticket));
        }

        alert()->flash(t('Ticket Info'), 'success', [
            'text' => t('Ticket has been re-assigned'),
            'timer' => 3000
        ]);

        return \Redirect::route('ticket.show', $ticket);
    }

    public function scope(Request $request)
    {
        \Session::put('ticket.scope', $request->get('scope')); // as set function deprecated by laravel
        return \Redirect::route('ticket.index');
    }

    public function duplicate(Ticket $ticket, Request $request)
    {
        Ticket::flushEventListeners();
        if ($request->tickets_count > 0 && $request->tickets_count <= 10) {
            for ($i = 1; $i <= $request->tickets_count; $i++) {
                $data = $ticket->toArray();
                unset($data['id'], $data['created_at'], $data['updated_at']);
                $newTicket = new Ticket($data);
                $newTicket->creator_id = \Auth::id();
                $newTicket->request_id = $ticket->id;
                $newTicket->status_id = 1;
                $newTicket->type = null;
                $newTicket->sdp_id = null;
                $newTicket->save();
                $this->duplicateTicketDetails($ticket, $newTicket);
                dispatch(new ApplySLA($newTicket));
//                $this->dispatch(new NewTicketJob($newTicket));
            }
            return \Redirect::route('ticket.show', $newTicket);
        }
        return \Redirect::back();

    }

    public function filter(Request $request)
    {
        session(['ticket.filter' => $request->get('criterions')]);

        return \Redirect::back();
    }

    public function clear()
    {
        \Session::remove('ticket.filter');

        return \Redirect::back();
    }

    public function addNote(Ticket $ticket, NoteRequest $request)
    {
        $note = TicketNote::create(['ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'note' => $request['note'],
            'display_to_requester' => $request->display_to_requester ? 1 : 0,
            'email_to_technician' => $request->email_to_technician ? 1 : 0,
            'as_first_response' => $request->as_first_response ? 1 : 0
        ]);
        if ($note->email_to_technician) {
            $this->dispatch(new NewNoteJob($note));
        }
        if ($note->as_first_response) {
            $this->dispatch(new ApplySLA($note->ticket));
        }
        alert()->flash(t('Ticket Info'), 'success', [
            'text' => t('Your note has been created'),
            'timer' => 3000
        ]);
        return \Redirect::route('ticket.show', $note->ticket);
    }

    public function editResolution(Ticket $ticket, TicketResolveRequest $request)
    {
        $ticket->replies()->where('status_id', 7)
            ->update(['content' => $request->get('content')]);

        alert()->flash(t('Ticket Info'), 'success', [
            'text' => t('Resolution saved successfully'),
            'timer' => 3000
        ]);

        return \Redirect::back();
    }


    public function editNote($note, Request $request)
    {
        $note = TicketNote::find($note);
        $validate = \Validator::make($request->all(), [
            'note' => 'required',
        ]);
        if ($validate->fails()) {
            alert()->flash(t('Ticket Info'), 'error', [
                'text' => t('Your note has not been updated'),
                'timer' => 3000
            ]);

            return \Redirect::route('ticket.show', $note->ticket);
        }
        $note->note = $request->note;
        $note->display_to_requester = $request->display_to_requester ? 1 : 0;
        $note->email_to_technician = $request->email_to_technician ? 1 : 0;
        $note->as_first_response = $request->as_first_response ? 1 : 0;
        $note->save();

        if ($note->email_to_technician) {
            $this->dispatch(new NewNoteJob($note));
        }
        alert()->flash(t('Ticket Info'), 'success', [
            'text' => t('Your note has been updated'),
            'timer' => 3000
        ]);
        return \Redirect::route('ticket.show', $note->ticket);
    }

    public function deleteNote($note)
    {
        $target_note = TicketNote::find($note);
        $ticket = $target_note->ticket;
        $target_note->delete();
        alert()->flash(t('Ticket Info'), 'success', [
            'text' => t('Your note has been deleted'),
            'timer' => 3000
        ]);
        return \Redirect::route('ticket.show', $ticket);
    }

    public function pickupTicket(Ticket $ticket)
    {
        if (can('pick', $ticket)) {
            if ($ticket->technician_id != \Auth::id()) {
                $ticket->technician_id = \Auth::user()->id;
                $ticket->update();
            }
        }
        return \Redirect::route('ticket.show', $ticket);
    }

    public function duplicateTicketDetails(Ticket $ticket, $newTicket)
    {
        Ticket::flushEventListeners();
        TicketReply::flushEventListeners();
        TicketApproval::flushEventListeners();
        Attachment::flushEventListeners();
        TicketNote::flushEventListeners();

        $ticket->replies->each(function ($reply) use ($newTicket) {
            if (!in_array($reply->status_id, [7, 8, 9])) {
                $reply->ticket_id = $newTicket->id;
                $reply['created_at'] = $reply->created_at;
                $reply['updated_at'] = $reply->updated_at;
                TicketReply::create($reply->toArray());
            }
        });

        $ticket->approvals->each(function ($approval) use ($newTicket) {
            $approval->ticket_id = $newTicket->id;
            $approval['created_at'] = $approval->created_at;
            $approval['updated_at'] = $approval->updated_at;
            TicketApproval::create($approval->toArray());
        });

        $ticket->notes->each(function ($note) use ($newTicket) {
            $note->ticket_id = $newTicket->id;
            $note['created_at'] = $note->created_at;
            $note['updated_at'] = $note->updated_at;
            TicketNote::create($note->toArray());
        });

        $ticket->logs->each(function ($log) use ($newTicket) {
            $log->ticket_id = $newTicket->id;
            $log['created_at'] = $log->created_at;
            $log['updated_at'] = $log->updated_at;
            TicketLog::create($log->toArray());
        });

        $ticket->fields->each(function ($field) use ($newTicket) {
            $field->ticket_id = $newTicket->id;
            $field['created_at'] = $field->created_at;
            $field['updated_at'] = $field->updated_at;
            TicketField::create($field->toArray());
        });

        $ticket->files->each(function ($file) use ($newTicket) {
            if ($file->type == 1) {
                $file->reference = $newTicket->id;
                $file['created_at'] = $file->created_at;
                $file['updated_at'] = $file->updated_at;
                Attachment::create($file->toArray());
            }
        });

    }

    function forward(Ticket $ticket, Request $request)
    {
        if ($request->get('to')) {
            $this->validate($request, ['to.*' => 'email'], ['email' => 'Please enter valid email.']);
            TicketReply::flushEventListeners();

            TicketReply::create([
                'user_id' => \Auth::id(),
                'ticket_id' => $ticket->id,
                'content' => $request["forward"]["description"] ?? $ticket->description,
                'status_id' => $ticket->status_id,
                'cc' => $request->cc ?? null,
                'to' => $request->to ?? null
            ]);

            \Mail::to($request->to)->cc($request->cc ?? [])->send(new TicketForwardJob($ticket));
            flash('Forward the ticket has been sent', 'success');
            return \Redirect::route('ticket.show', $ticket);
        }
        flash('Can\'t forward the ticket', 'danger');
        return \Redirect::route('ticket.show', $ticket);
    }

    function selectCategory(BusinessUnit $business_unit)
    {
        if ($business_unit->categories()->count()) {
            return view('ticket.create_ticket.create_category', compact('business_unit'));
        }
        return view('ticket.create', compact('business_unit'));
    }

    function selectSubcategory(BusinessUnit $business_unit, Category $category)
    {

        if ($category->subcategories()->count()) {
            return view('ticket.create_ticket.create_subcategory', compact('business_unit', 'category'));
        }
        $subcategory = new Subcategory();

        return view('ticket.create', compact('business_unit', 'category', 'subcategory'));
    }

    function selectItem(BusinessUnit $business_unit, Category $category, Subcategory $subcategory)
    {
        if ($subcategory->items()->count()) {
            return view('ticket.create_ticket.create_item', compact('business_unit', 'category', 'subcategory'));
        }

        $item = null;
        return view('ticket.create', compact('business_unit', 'category', 'subcategory', 'item'));
    }

}
