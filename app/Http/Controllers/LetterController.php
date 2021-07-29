<?php

namespace App\Http\Controllers;

use App\Item;
use App\Letter;
use App\LetterGroup;
use App\LetterTicket;
use App\Ticket;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    function index()
    {
        $letters = Letter::paginate();
        return view('letters_admin.letter.index', compact('letters'));
    }

    function create()
    {
        return view('letters_admin.letter.create');
    }

    function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        Letter::create($request->all());

        return redirect()->route('letters.letter.index');
    }

    function edit(Letter $letter)
    {
        return view('letters_admin.letter.edit', compact('letter'));
    }

    function update(Letter $letter, Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        $letter->update($request->all());

        return redirect()->route('letters.letter.index');
    }

    function destroy(Letter $letter)
    {


    }


    function createLetterTicket(Request $request)
    {
        /** @var Item $item */
        $item = Item::find($request->item_id);

        Ticket::flushEventListeners();

        $ticket = Ticket::create([
            'subject' => $request->subject,
            'description' => $request->description,
            'category_id' => $item->subcategory->category->id,
            'subcategory_id' => $item->subcategory->id,
            'item_id' => $item->id,
            'requester_id' => auth()->id(),
            'creator_id' => auth()->id(),
            'group_id' => config('letters.group'),
            'status_id' => config('letters.new_letter_status'),
            'business_unit_id' => auth()->user()->business_unit_id,
        ]);

        $letterTicket = LetterTicket::create([
            'ticket_id' => $ticket->id,
            'group_id' => $request->group_id,
            'subgroup_id' => $request->subgroup_id,
            'letter_id' => $request->letter_id,
            'need_coc_stamp' => $request->is_stamped,
        ]);
        //upload Attachments


    }
}
