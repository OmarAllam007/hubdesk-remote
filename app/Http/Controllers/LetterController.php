<?php

namespace App\Http\Controllers;

use App\CustomField;
use App\Helpers\ChromePrint;
use App\Helpers\LetterSponserMap;
use App\Item;
use App\Jobs\NewTicketJob;
use App\Letter;
use App\LetterField;
use App\LetterGroup;
use App\LetterTicket;
use App\Ticket;
use App\User;
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

        $requesterId = in_array($request->requester_id, ['', 'undefined']) ? auth()->id() : $request->requester_id;

        $ticket = Ticket::create([
            'subject' => $request->subject,
            'description' => $request->description ?? '',
            'category_id' => $item->subcategory->category->id,
            'subcategory_id' => $item->subcategory->id,
            'item_id' => $item->id,
            'requester_id' => $requesterId,
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

        if ($request->get('fields', [])) {
            foreach (json_decode($request->get('fields', []), true) as $key => $item) {

                if ($item) {
                    $field = LetterField::find($key)->name ?? '';

                    if (is_array($item) && count($item) > 0) {
                        $item = implode(", ", $item);
                    }
                    $ticket->fields()->create(['name' => $field, 'value' => $item]);
                }
            }
        }

        return response()->json(['ticket' => $ticket]);
    }


    protected $userActive = true;

    function buildView($ticket)
    {
        $ticketRef = $ticket;

        $employeeID = $ticketRef->requester->employee_id;
        if ($ticketRef->is_letter_ticket) {
            $letterTicketRef = $ticketRef->letter_ticket;

            if ($letterTicketRef->to_user_id) {
                $employeeID = $letterTicketRef->user->employee_id;
            }
        }

        if ($ticketRef->isTask()) {
            $employeeID = $ticketRef->ticket->requester->employee_id;
        }
        $user = \App\User::where('employee_id', $employeeID)->first();

        $sapApi = new \App\Helpers\SapApi($user);
        $sapApi->getUserInformation();

        $user = $sapApi->sapUser->getEmployeeSapInformation();
        $this->userActive = $user['is_active'];

        if (!$user['is_active']) {
            return false;
        }

        $user['allowances_str'] = $sapApi->sapUser->getAllowancesString();


        $letterTicket = LetterTicket::where('ticket_id', $ticketRef->isTask() ? $ticketRef->ticket->id : $ticket->id)->first();
        $view = $letterTicket->letter->view_path;


        $letterTicket['header'] = LetterSponserMap::$systemBusinessUnits[$user['sponsor_id']];
        $letterTicket['stamp'] = '/stamps/' . LetterSponserMap::$systemBusinessUnits[$user['sponsor_id']] . '/image.jpg';
        $letterTicket['signature'] = User::find(1309)->signature;

        return view("letters.template.${view}", compact('user', 'letterTicket'));
    }


    function generateLetter($ticket)
    {
        $ticket = Ticket::find($ticket);

        if ($ticket->item_id == 445) { // to be better than this
            $content = $this->buildExperienceCertificate($ticket);
        } else {
            $content = $this->buildView($ticket);
        }

        if (!$content) {
            return view('letters.contact_hr');
        }

        $filepath = storage_path('app/letter.html');
        file_put_contents($filepath, $content->render());
        $print = new ChromePrint($filepath);
        $file = $print->print();

        return response()->download($file, null, [], 'inline')->deleteFileAfterSend();
    }

    function generateLetterDoc($ticket)
    {
        $ticketObj = Ticket::find($ticket);

        $employeeID = $ticketObj->isTask() ? $ticketObj->ticket->requester->employee_id : $ticketObj->requester->employee_id;
        $letterTicketObj = $ticketObj->isTask() ? $ticketObj->ticket->id : $ticketObj->id;

        $user = \App\User::where('employee_id', $employeeID)->first();
        $sapApi = new \App\Helpers\SapApi($user);

        $sapApi->getUserInformation();

        $user = $sapApi->sapUser->getEmployeeSapInformation();

        $letterTicket = \App\LetterTicket::where('ticket_id', $letterTicketObj)->first();
        $letterPath = str_replace('.', '/', $letterTicket->letter->view_path);

        require base_path("resources/views/letters/word/{$letterPath}.php");
    }

    function getLetterContent(Ticket $ticket)
    {
        $letterTicket = $ticket->letter_ticket;

        $user = \App\User::where('employee_id', $ticket->requester->employee_id)->first();

        $sapApi = new \App\Helpers\SapApi($user);
        $sapApi->getUserInformation();
        $user = $sapApi->sapUser->getEmployeeSapInformation();

        $letterTicket['header'] = LetterSponserMap::$systemBusinessUnits[$user['sponsor_id']];
        $letterTicket['stamp'] = '/stamps/' . LetterSponserMap::$systemBusinessUnits[$user['sponsor_id']] . '/image.jpg';
        $letterTicket['signature'] = User::find(1148)->signature;

        return view('letters.template.' . $ticket->letter_ticket->letter->view_path,
            compact('letterTicket', 'user'))->render();

    }

    function convertToLetter(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);

        if ($ticket->is_letter_ticket) {
            LetterTicket::where('ticket_id', $ticket->id)->delete();
        }

        $ticket->update([
            'category_id' => 112,
            'subcategory_id' => 407,
            'item_id' => 368
        ]);

        if ($request->has('fields') && count($request->fields)) {
            foreach ($request->fields as $field) {
                $ticket->fields()->create([
                    'name' => $field['name'],
                    'value' => $field['value']
                ]);
            }
        }

        LetterTicket::create([
            'ticket_id' => $ticket->id,
            'group_id' => $request->group_id,
            'subgroup_id' => $request->subgroup_id,
            'letter_id' => $request->letter_id,
            'need_coc_stamp' => $request->is_stamped,
            'to_user_id' => $request->requester_id
        ]);
    }

    function verifyLetterView($ticketId)
    {
        $ticketDecryptedID = (int)\Crypt::decryptString($ticketId);
        $ticketModel = Ticket::find($ticketDecryptedID);

        if (!$ticketModel) {
            return;
        }

        $view = $this->buildView($ticketModel)->render();
        return view('letters.verification.index', compact('ticketDecryptedID', 'view'));
    }

    private function buildExperienceCertificate(Ticket $ticket)
    {
        $employeeID = $ticket->fields()->where('name','Employee Number')->first();
        $user = \App\User::where('employee_id', $employeeID->value)->first();

        $sapApi = new \App\Helpers\SapApi($user);
        $sapApi->getUserInformation();

        $user = $sapApi->sapUser->getEmployeeSapInformation();
        $this->userActive = $user['is_active'];

        if (!$user['is_active']) {
            return false;
        }

        $letterTicket = $ticket;
        $letterTicket['header'] = LetterSponserMap::$systemBusinessUnits[$user['sponsor_id']];
        $letterTicket['stamp'] = '/stamps/' . LetterSponserMap::$systemBusinessUnits[$user['sponsor_id']] . '/image.jpg';
        $letterTicket['signature'] = User::find(1309)->signature;

        return view("letters.template.experience_certificate.experience_certificate", compact('user', 'letterTicket','employeeID'));
    }
}
