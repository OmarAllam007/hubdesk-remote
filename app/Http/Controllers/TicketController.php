<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\BusinessUnit;
use App\Category;
use App\CustomField;
use App\Helpers\Ticket\TicketViewScope;
use App\Http\Requests\ReassignRequest;
use App\Http\Requests\TicketReplyRequest;
use App\Http\Requests\TicketRequest;
use App\Http\Requests\TicketResolveRequest;
use App\Http\Resources\TicketReplyResource;
use App\Http\Resources\TicketResource;
use App\Item;
use App\Jobs\ApplySLA;
use App\Jobs\NewTicketJob;
use App\Jobs\TicketReplyJob;
use App\LetterGroup;
use App\Mail\TicketAssignedMail;
use App\Mail\TicketComplaint;
use App\Mail\TicketForwardJob;
use App\Priority;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TicketController extends Controller
{
    public function index()
    {
        $ticketScope = $this->handleTicketsScope();
        $query = $ticketScope['query'];
        $scope = $ticketScope['scope'];
        $scopes = $ticketScope['scopes'];

        if ($search = \request('search')) {
            $ticket = Ticket::where('id', intval($search))->first();

            if ($ticket) {
                return redirect()->route('ticket.show', compact('ticket'));
            } else {
                $user = auth()->user();
                $searchedTickets = Ticket::query()->with('requester');

                if ($user->isSupport()) {
                    $searchedTickets = $searchedTickets->where(function ($q) use ($user, $search) {
                        $q->whereHas('requester', function (Builder $query) use ($search) {
                            $query->where('employee_id', $search)
                                ->orWhere('name', 'LIKE', "%${search}%");
                        })->whereIn('group_id', $user->groups->pluck('id'));

                    });
                } else {
                    if ($search == $user->employee_id) {
                        $searchedTickets = $searchedTickets->where('requester_id', $user->id)
                            ->orWhere('creator_id', $user->id);
                    } else {
                        $searchedTickets = collect();
                    }
                }
            }

            if ($searchedTickets->count() > 1) {
                $query = $searchedTickets;
            } else {
                flash(t('Ticket Info'), t('No Results Found!'), 'error');
                return redirect()->route('ticket.index');
            }

        }


        return view('ticket.index');
//        return $query->latest('id')->paginate();
//        return view('ticket.index', compact('tickets', 'scopes', 'scope'));
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
        $ticket = new Ticket($request->all());

        $request['business_unit_id'] = $ticket->requester->business_unit_id;

        $ticket->client_info = ['client' => $request->userAgent(), 'ip_address' => $request->ip(), 'client_ip' => $request->getClientIp()];
        $ticket->save();


        foreach ($request->get('cf', []) as $key => $item) {
            if ($item) {
                if(is_numeric($key)){
                    $field = CustomField::find($key)->name ?? '';

                    if (is_array($item) && count($item) > 0) {
                        $item = implode(", ", $item);
                    }
                }else{
                    $field = $key;
                }

                $ticket->fields()->create(['name' => $field, 'value' => $item]);
            }
        }


        flash(t('Ticket Info'), t('Ticket has been saved'), 'success');
        return \Redirect::route('ticket.show', $ticket);
    }

    public function show(Ticket $ticket)
    {

//        \Session::remove('recent-tickets-' . auth()->id());
        $this->updateLastTickets($ticket);

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

        if ($request->has('template') && $template_id = $request->get('template')) {
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
        $ticket->update($request->only(['group_id', 'technician_id', 'category_id', 'subcategory_id', 'item_id', 'subitem_id']));

        \Mail::queue(new TicketAssignedMail($ticket));

        return \response()->json(['message' => 'Ticket reassigned successfully', 'new_ticket' => TicketResource::make($ticket)]);
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
            }
            return \response()->json(['message' => 'Ticket Created successfully', 'ticket' => $newTicket]);
        }
        return \response()->json(['message' => 'Number of tickets exceed 10 copies']);

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


    public function editResolution(Ticket $ticket, TicketResolveRequest $request)
    {
        $ticket->replies()->where('status_id', 7)
            ->update(['content' => $request->get('content')]);

        flash(t('Ticket Info'), t('Resolution saved successfully'), 'success');

        return \Redirect::back();
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
        $to = collect($request->get('to'))->pluck('id')->toArray();
        $cc = collect($request->get('cc'))->pluck('id')->toArray();

        $toUsers = User::whereIn('id', $to)->get(['email'])->pluck('email', 'email')->toArray();
        $ccUsers = User::whereIn('id', $cc)->get(['email'])->pluck('email', 'email')->toArray();
        TicketReply::flushEventListeners();

        $message = $request->get('description') ?? $ticket->description;

        $reply = TicketReply::create([
            'user_id' => \Auth::id(),
            'ticket_id' => $ticket->id,
            'content' => $message,
            'status_id' => $ticket->status_id,
            'to' => $toUsers ?? null,
            'cc' => $ccUsers ?? null,
        ]);

        \Mail::to($toUsers)->cc($ccUsers ?? [])->queue(new TicketForwardJob($ticket, $message));

        return \response()->json(['message' => 'Email send successfully', 'reply' => TicketReplyResource::make($reply)]);
//        flash(t('Forward Info'), 'Forward the ticket has been sent', 'success');
//        return \Redirect::route('ticket.show', $ticket);

//        }

//        flash(t('Forward Info'), 'Can\'t forward the ticket', 'danger');
//        return \Redirect::route('ticket.show', $ticket);
    }

    function complaint(Ticket $ticket, Request $request)
    {
//        $this->validate($request, ['complaint.description' => 'required'],
//            ['complaint.description.required' => 'The description field is required']);

        $userComplaint = UserComplaint::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->user()->id,
            'description' => $request->get('description') ?? '',
            'type' => $request->get('type')
        ]);

        $complaint = $ticket->complaint;

        if ($complaint) {
            $to = User::whereIn('id', $complaint->to)->get(['email']);
            $cc = User::whereIn('id', $complaint->cc)->get(['email']);


            \Mail::to($to)->cc($cc)->queue(new TicketComplaint($ticket, $userComplaint));
        }

        return \response()->json(['message' => 'Complaint sent successfully']);
    }


    function selectCategory(BusinessUnit $business_unit)
    {
        return view('ticket.create_ticket.select_category', compact('business_unit'));
    }

    function selectSubcategory(BusinessUnit $business_unit, Category $category)
    {
        if (in_array($category->id,[116,160]) || $category->subcategories()->count() >= 1) {
            return view('ticket.create_ticket.select_subcategory', compact('business_unit', 'category'));
        }
//        elseif ($category->subcategories()->count() == 1) {
//            $subcategory = $category->subcategories()->first();
//            return redirect()->route('ticket.create.select_item', compact('business_unit', 'subcategory'));
//        }


        $subcategory = new Subcategory();
        $item = new Item();
        return view('ticket.create', compact('business_unit', 'category', 'subcategory','item'));
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

        if ($item->id == config('letters.item_id')) {
            return $this->redirectToLetters($item);
        }

        return view($item->custom_path ?? 'ticket.create',
            compact('business_unit', 'category', 'subcategory', 'item', 'subItem'));
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

    private function redirectToLetters(Item $item)
    {
        $groups = LetterGroup::where('parent_group_id', 0)
            ->orderBy('order')->get(['id', 'name']);

        $priorities = Priority::all();

        $category = $item->subcategory->category;
        $subcategory = $item->subcategory;

        $subject = (auth()->user()->employee_id ? auth()->user()->employee_id . ' - ' : '') .
            t($category->name) . (isset($subcategory->name) ? '  -  ' .
                t($subcategory->name) : '') . (isset($item->name) ? '  -  ' .
                t($item->name) : '') . (isset($subItem->name) ? '  -  ' .
                $subItem->name : '');

        return view('letters.ticket.create', compact('item', 'groups', 'priorities', 'subject'));
    }

    private function updateLastTickets($ticket)
    {
        $lastTickets = \Session::get('recent-tickets-' . auth()->id(), []);

        if (!in_array($ticket->id, $lastTickets)) {
            array_unshift($lastTickets, $ticket->id);
        } else {
            $index = array_search($ticket->id, $lastTickets);
            unset($lastTickets[$index]);
            array_unshift($lastTickets, $ticket->id);
        }

        session()->put('recent-tickets-' . auth()->id(), array_slice($lastTickets, 0, 10));
    }
}