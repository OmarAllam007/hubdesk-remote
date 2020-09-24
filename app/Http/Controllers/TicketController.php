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
use App\Jobs\ApplyBusinessRules;
use App\Jobs\ApplySLA;
use App\Jobs\NewNoteJob;
use App\Jobs\NewTicketJob;
use App\Jobs\TicketAssigned;
use App\Jobs\TicketReplyJob;
use App\Mail\TicketAssignedMail;
use App\Mail\TicketComplaint;
use App\Mail\TicketForwardJob;
use App\ReplyTemplate;
use App\Subcategory;
use App\SubItem;
use App\Ticket;
use App\TicketApproval;
use App\TicketField;
use App\TicketLog;
use App\TicketNote;
use App\TicketReply;
use App\User;
use App\UserComplaint;
use http\Env\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $ticketScope = $this->handleTicketsScope();
        $query = $ticketScope['query'];
        $scope = $ticketScope['scope'];
        $scopes = $ticketScope['scopes'];

        if ($search = \request('search')) {

            $searchedTickets = Ticket::with('requester')->where('id', intval($search))
                ->orWhereHas('requester', function (Builder $query) use ($search) {
                    $query->where('employee_id', $search)
                        ->orWhere('name', 'LIKE', "%${search}%");
                });

            if ($searchedTickets->count() > 1) {
                $query = $searchedTickets;
            } else if ($searchedTickets->count() == 1) {
                $ticket = $searchedTickets->first()->id;
                return redirect()->route('ticket.show', compact('ticket'));
            } else {
                flash(t('Ticket Info'), t('Ticket not found'), 'error');
                return redirect()->route('ticket.index');
            }

        }


        $tickets = $query->latest('id')->paginate();
        return view('ticket.index', compact('tickets', 'scopes', 'scope'));
    }

    public function create()
    {
        return view('ticket.create_ticket.index');
    }

    public function createTicket(BusinessUnit $business_unit, Category $category, Subcategory $subcategory, Item $item, SubItem $subItem)
    {
        return view('ticket.create', compact('business_unit', 'category', 'subcategory', 'item', 'subItem'));
    }

    public function store(TicketRequest $request)
    {
        $request['business_unit_id'] = auth()->user()->business_unit_id;
        $ticket = new Ticket($request->all());
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

        $this->dispatch(new NewTicketJob($ticket));

        flash(t('Ticket Info'), t('Ticket has been saved'), 'success');
        return \Redirect::route('ticket.show', $ticket);
    }

    public function show(Ticket $ticket)
    {
        Ticket::flushEventListeners();
        if (\Auth::user()->id == $ticket->technician_id) {
            $ticket->update(['is_opened' => 1]);
        }

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

        flash(t('Ticket Info'), t('Ticket has been deleted'), 'success');

        return \Redirect::route('ticket.index');
    }

    public function reply(Ticket $ticket, TicketReplyRequest $request)
    {
        if (isset($request->get("reply")["cc"])) {
            $this->validate($request, ['reply.cc.*' => 'email'], ['email' => 'Please enter valid emails']);
        }
//        if (in_array($request->reply['status_id'], [7, 8, 9]) && $ticket->hasOpenTask()) {
//
//            flash(t('Ticket Info'), t('Ticket has pending tasks'), 'error');
//            return \Redirect::route('ticket.show', compact('ticket'));
//        }
        $reply = new TicketReply($request->get('reply'));
        $reply->user_id = $request->user()->id;
        $reply->cc = $request->get("reply")["cc"] ?? null;

        if (isset($request->get('reply')['template']) && $template_id = $request->get('reply')['template']) {
            $reply["content"] = ReplyTemplate::find($template_id)->description;
        }
        // Fires creating event in \App\Providers\TicketReplyObserver


        if ($ticket->status_id == 8 && $request->get('reply')['status_id'] != 8) {
            if (can('reopen', $ticket)) {
                $ticket->replies()->save($reply);
            } else {
                flash(t('Ticket Info'), t('Can\'t change closed ticket status'), 'danger');
                return \Redirect::route('ticket.show', compact('ticket'));
            }
        } else {
            // Fires creating event in \App\Providers\TicketReplyEventProvider
            $ticket->replies()->save($reply);
//
//        //@todo: Calculate elapsed time
            flash(t('Reply Info'), t('Reply has been added'), 'success');
            return redirect()->back();
        }

    }

    public function resolution(Ticket $ticket, TicketResolveRequest $request)
    {
        if ($ticket->hasOpenTask()) {
            flash(t('Ticket Info'), t('Ticket has pending tasks'), 'danger');
            return \Redirect::route('ticket.show', compact('ticket'));
        }

        if (isset($request->get('reply')['template']) && $template_id = $request->get('template')) {
            $request['content'] = ReplyTemplate::find($template_id)->description;
        }

        $data = ['content' => $request->get('content'),
            'status_id' => 7, 'user_id' => $request->user()->id];

        // Fires creating event in \App\Providers\TicketReplyEventProvider
        $reply = $ticket->replies()->create($data);

        //@todo: Calculate elapsed time
        $this->dispatch(new TicketReplyJob($reply));

        flash(t('Ticket Info'), t('Ticket has been resolved'), 'success');
        return redirect()->back();
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

        flash(t('Ticket Info'), t('Ticket not found'), 'error');
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

        $ticket->update($request->only(['group_id', 'technician_id', 'category_id', 'subcategory_id', 'item_id', 'subitem_id']));

        if ($request->get('technician_id') != $current_technician) {
            \Mail::send(new TicketAssignedMail($ticket));
        }

        flash(t('Ticket Info'), t('Ticket has been re-assigned'), 'success');

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
        flash(t('Note Info'), t('Your note has been created'), 'success');
        return \Redirect::route('ticket.show', $note->ticket);
    }

    public function editResolution(Ticket $ticket, TicketResolveRequest $request)
    {
        $ticket->replies()->where('status_id', 7)
            ->update(['content' => $request->get('content')]);

        flash(t('Ticket Info'), t('Resolution saved successfully'), 'success');

        return \Redirect::back();
    }


    public function editNote($note, Request $request)
    {
        $note = TicketNote::find($note);
        $validate = \Validator::make($request->all(), [
            'note' => 'required',
        ]);
        if ($validate->fails()) {
            flash(t('Ticket Info'), t('Your note has not been updated'), 'error');

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
        flash(t('Ticket Info'), t('Your note has been updated'), 'success');
        return \Redirect::route('ticket.show', $note->ticket);
    }

    public function deleteNote($note)
    {
        $target_note = TicketNote::find($note);
        $ticket = $target_note->ticket;
        $target_note->delete();

        flash(t('Ticket Info'), t('Your note has been deleted'), 'success');
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

            flash(t('Forward Info'), 'Forward the ticket has been sent', 'success');
            return \Redirect::route('ticket.show', $ticket);

        }

        flash(t('Forward Info'), 'Can\'t forward the ticket', 'danger');
        return \Redirect::route('ticket.show', $ticket);
    }

    function complaint(Ticket $ticket, Request $request)
    {
        $this->validate($request, ['complaint.description' => 'required'],
            ['complaint.description.required' => 'The description field is required']);

        UserComplaint::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->user()->id,
            'description' => $request->complaint['description'] ?? '',
        ]);

        $complaint = $ticket->complaint;

        if ($complaint) {
            $to = User::whereIn('id', $complaint->to)->get(['email']);
            $cc = User::whereIn('id', $complaint->cc)->get(['email']);

            \Mail::to($to)->cc($cc)->send(new TicketComplaint($ticket));
        }

        flash(t('Complaint Info'), 'Complaint has been sent', 'success');
        return \Redirect::route('ticket.show', $ticket);
    }


    function selectCategory(BusinessUnit $business_unit)
    {
        return view('ticket.create_ticket.select_category', compact('business_unit'));
    }

    function selectSubcategory(BusinessUnit $business_unit, Category $category)
    {
        if ($category->id == 116 && $category->subcategories()->count()) {
            return view('ticket.create_ticket.select_subcategory', compact('business_unit', 'category'));
        }

        $subcategory = new Subcategory();
        return view('ticket.create', compact('business_unit', 'category', 'subcategory'));
    }

    function selectItem(BusinessUnit $business_unit, Subcategory $subcategory)
    {
        if ($subcategory->items()->count()) {
            return view('ticket.create_ticket.select_item', compact('business_unit', 'subcategory'));
        }

        $category = $subcategory->category;
        $item = new Item();

        return view('ticket.create', compact('business_unit', 'category', 'subcategory', 'item'));
    }

    function selectSubItem(BusinessUnit $business_unit, Item $item)
    {

        if ($item->subItems()->count()) {
            return view('ticket.create_ticket.select_subitem', compact('business_unit', 'item'));
        }

        $category = $item->subcategory->category;
        $subcategory = $item->subcategory;
        $subItem = null;

        return view('ticket.create', compact('business_unit', 'category', 'subcategory', 'item', 'subItem'));
    }


    function downloadAttachment(Attachment $attachment)
    {
        if (in_array($attachment->type, [Attachment::TICKET_TYPE, Attachment::TASK_TYPE])) {
            $ticket = Ticket::find($attachment->reference);
        } elseif ($attachment->type == Attachment::TICKET_REPLY_TYPE) {
            $ticket = TicketReply::find($attachment->reference)->ticket;
        } else {
            $ticket = TicketApproval::find($attachment->reference)->ticket;
        }

        $this->authorize('show', $ticket);

        $file = public_path('storage') . $attachment->path;
        return response()->download($file);
    }
}
