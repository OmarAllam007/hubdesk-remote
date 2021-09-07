<?php

namespace App\Http\Controllers;

use App\Helpers\ChromePrint;
use App\Item;
use App\Jobs\NewTicketJob;
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

        if ($request->has('fields') && count($request->fields)) {
            foreach ($request->fields as $field) {
                $ticket->fields()->create([
                    'name' => $field['name'],
                    'value' => $field['value']
                ]);
            }
        }

        $letterTicket = LetterTicket::create([
            'ticket_id' => $ticket->id,
            'group_id' => $request->group_id,
            'subgroup_id' => $request->subgroup_id,
            'letter_id' => $request->letter_id,
            'need_coc_stamp' => $request->is_stamped,
        ]);

    }

    function generateLetter($ticket)
    {

        $ticketRef = Ticket::find($ticket)->first();
        $user = \App\User::where('employee_id', $ticketRef->requester->employee_id)->first();

        $sapApi = new \App\Helpers\SapApi($user);
        $sapApi->getUserInformation();
        $user = $sapApi->sapUser->getEmployeeSapInformation();
        $user['allowances_str'] = $sapApi->sapUser->getAllowancesString();

        $letterTicket = LetterTicket::where('ticket_id', $ticket)->first();
        $view = $letterTicket->letter->view_path;

        /* @TODO to be changes*/
        $letterTicket['header'] = 8;
        $letterTicket['stamp'] = '/stamps/8/stamp_test.png';
        $letterTicket['signature'] = '/stamps/8/signature.png';
        $content = view("letters.template.${view}", compact('user', 'letterTicket'));

        $filepath = storage_path('app/letter.html');
        file_put_contents($filepath, $content->render());
        $print = new ChromePrint($filepath);
        $file = $print->print();

        return response()->download($file, null, [], 'inline')->deleteFileAfterSend();
    }

    function generateLetterDoc(Ticket $ticket)
    {
        $user = \App\User::where('employee_id', $ticket->requester->employee_id)->first();
        $sapApi = new \App\Helpers\SapApi($user);
        $sapApi->getUserInformation();
        $user = $sapApi->sapUser->getEmployeeSapInformation();
        $letterTicket = \App\LetterTicket::where('ticket_id', $ticket->id)->first();
        $letterPath = str_replace('.', '/', $letterTicket->letter->view_path);

        require base_path("resources/views/letters/word/{$letterPath}.php");
    }
}
