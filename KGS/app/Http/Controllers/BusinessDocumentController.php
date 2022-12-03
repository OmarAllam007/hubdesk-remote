<?php
/**
 * Created by PhpStorm.
 * User: omarkhaled
 * Date: 2019-03-10
 * Time: 10:55
 */

namespace KGS\Http\Controllers;


use App\Attachment;
use App\BusinessUnit;
use App\Category;
use App\CustomField;
use App\Division;
use App\DocumentRequirements;
use App\DocumentTicket;
use App\GrRequirements;
use App\Http\Controllers\Controller;
use App\Item;
use App\Jobs\NewTicketJob;
use App\Subcategory;
use App\Task;
use App\Ticket;
use App\TicketField;
use App\TicketRequirements;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use KGS\Document;
use KGS\DocumentNotification;
use KGS\Requirement;
use Predis\Response\Status;
use function GuzzleHttp\Promise\all;

class BusinessDocumentController extends Controller
{
    function selectDivision()
    {
        return view('kgs::business-documents.wizard.select_division');
    }

    function index(Division $division)
    {
        return view('kgs::business-documents.wizard.index', compact('division'));
    }

    function createTicketCheck(Document $document)
    {
        $business_unit = $document->folder->business_unit_id;

        if ($document->level) {
            if (is_a(app(Item::class), $document->level)) {
                $item = Item::find($document->level_id);
                $subcategory = $item->subcategory;
                $category = $subcategory->category;

                return redirect()->route('kgs.document.check-requirements', compact('business_unit', 'category', 'subcategory', 'item'));

            } elseif (is_a(app(Subcategory::class), $document->level)) {
                $subcategory = Subcategory::find($document->level_id);
                $category = $subcategory->category;

                return redirect()->route('kgs.document.select_item', compact('business_unit', 'category', 'subcategory'));
            }
        }

        return redirect()->route('kgs.document.select_category', ['business_unit' => $business_unit]);
    }

    function selectCategory(BusinessUnit $business_unit)
    {
        return view('kgs::business-documents.wizard.select_category', compact('business_unit'));
    }

    function selectSubcategory(BusinessUnit $business_unit, Category $category)
    {
        return view('kgs::business-documents.wizard.select_subcategory', compact('business_unit', 'category'));
    }

    function selectItem(BusinessUnit $business_unit, Category $category, Subcategory $subcategory)
    {
        if ($subcategory->items->count()) {
            return view('kgs::business-documents.wizard.select_item', compact('business_unit', 'category', 'subcategory'));
        } else {
            $requirements = $category->requirements->merge($subcategory->requirements);
            if ($requirements->count()) {
                return view('kgs::business-documents.wizard.requirements', compact('requirements', 'business_unit', 'category', 'subcategory'));
            }
            $item = null;
            return view('kgs::business-documents.wizard.create_ticket', compact('business_unit', 'category', 'subcategory', 'item', 'requirements'));

        }
    }

    function checkForRequirements(BusinessUnit $business_unit, Category $category, Subcategory $subcategory, Item $item)
    {
        $requirements = $category->requirements->merge($subcategory->requirements);
        $requirements = $requirements->merge($item->requirements)->each(function ($requirement) {
            $requirement['cost'] = $requirement->service_cost;
        });

        if ($requirements->count()) {
            return view('kgs::business-documents.wizard.requirements', compact('business_unit', 'category', 'subcategory', 'item', 'requirements'));
        } else {
            return view('kgs::business-documents.wizard.create_ticket', compact('business_unit', 'category', 'subcategory', 'item', 'requirements'));
        }

    }


    function createTicket(BusinessUnit $business_unit, Category $category, Subcategory $subcategory, Item $item)
    {
        $label = $this->getServicesLabels($category, $subcategory, $item);

        $ticket = Ticket::create([
            'requester_id' => \Auth::id(),
            'creator_id' => \Auth::id(),
            'subject' => $label,
            'description' => \request('description', ''),
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id ?? null,
            'item_id' => $item->id ?? null,
            'status_id' => $this->checkForStatus(),
            'business_unit_id' => $business_unit->id
        ]);


        foreach (\request()->get('cf', []) as $key => $item) {
            if ($item) {
                $field = CustomField::find($key)->name ?? '';

                if (is_array($item) && count($item) > 0) {
                    $item = implode(", ", $item);
                }
                $ticket->fields()->create(['name' => $field, 'value' => $item]);
            }
        }

        $this->uploadTicketAttachments($ticket, \request('ticket-attachments'));
        $this->handleTicketRequirements($ticket);
        $this->dispatch(new NewTicketJob($ticket));
        return redirect()->route('ticket.show', $ticket->id);
    }

    private function getServiceLevels($task)
    {
        if ($task["type"] == 2) {
            return;
        }
        if ($task['reference_type'] == Requirement::ITEM_TYPE) {
            $item = Item::find($task['reference']);
            $subcategory = $item->subcategory;
            $category = $subcategory->category;
            return compact('category', 'subcategory', 'item');
        } else if ($task['reference_type'] == Requirement::SUBCATEGORY_TYPE) {
            $subcategory = Subcategory::find($task['reference']);
            $category = $subcategory->category;
            $item = null;
            return compact('category', 'subcategory', 'item');
        }

        return Category::find($task['reference']);
    }

    private function getServicesLabels(Category $category, Subcategory $subcategory, Item $item)
    {
        if ($subcategory && $item->id) {
            return $category->name . ' > ' . $subcategory->name . ' > ' . $item->name;
        }
        if ($subcategory && !$item->id) {
            return $category->name . ' > ' . $subcategory->name;
        }
        return $category->name;
    }

    private function uploadTicketAttachments($ticket, $files)
    {
        Attachment::flushEventListeners();

        if (!empty($files)) {
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();

                $folder = storage_path('app/public/attachments/' . $ticket->id . '/');
                if (!is_dir($folder)) {
                    mkdir($folder, 0775, true);
                }

                $path = $folder . $filename;
                if (is_file($path)) {
                    $filename = uniqid() . '_' . $filename;
                    $path = $folder . $filename;
                }

                $file->move($folder, $filename);

                Attachment::create([
                    'type' => Attachment::TICKET_TYPE,
                    'reference' => $ticket->id,
                    'path' => '/attachments/' . $ticket->id . '/' . $filename,
                ]);
            }
        }
    }

    private function checkForStatus()
    {
        $tasks = \request()->get('requirements', []);
        $tasks_files = \request()->allFiles();

        $all_checked = count($tasks) == count($tasks_files['requirements'] ?? []);

        if ($all_checked) {
            return 1;
        } else {
            return 4;
        }
    }

    function manageNotification(BusinessUnit $business_unit)
    {
        $users = User::whereNotNull('email')->get();
        return view('kgs::business-documents.notifications.show', compact('business_unit', 'users'));
    }

    function saveNotification(BusinessUnit $business_unit, Request $request)
    {
        $this->validate($request, ['notifications.*.users' => ['required']]);
        if (!empty($request->notifications)) {
            foreach ($request->notifications as $key => $notification) {
                $document = DocumentNotification::where('business_unit_id', $business_unit->id)
                    ->where('level', ++$key)->first();
                if ($document) {
                    $document->update([
                        'days' => $notification['days'],
                        'users' => $notification['users']
                    ]);
                } else {
                    DocumentNotification::create([
                        'business_unit_id' => $business_unit->id,
                        'level' => $key,
                        'days' => $notification['days'],
                        'users' => $notification['users']
                    ]);
                }

            }
        }
        return redirect()->route('kgs.document.select_category', compact('business_unit'));
    }


    private function handleTicketRequirements(Ticket $ticket)
    {
        $tasks_files = \request()->allFiles();

        foreach (\request('requirements', []) as $index => $requirement) {
            if ($requirement['type'] == Requirement::SERVICE_TYPE) {
                if (isset($requirement['checked'])) {
                    $this->uploadTicketAttachments($ticket, [$tasks_files['requirements'][$index]['file']]);
                } else {
                    $this->createNewTask($ticket, $requirement);
                }
            } elseif ($requirement['type'] == Requirement::DOCUMENT_TYPE && isset($requirement['checked'])) {
                $this->uploadTicketAttachments($ticket, [$tasks_files['requirements'][$index]['file']]);
            } elseif ($requirement['type'] == Requirement::INPUT_TYPE) {
                $ticket->fields()->create([
                    'name' => Requirement::find($requirement['id'])->field,
                    'value' => $requirement['input']
                ]);
            }
        }
    }

    private function createNewTask($ticket, $requirement)
    {
        $levels = $this->getServiceLevels($requirement);
        $task_label = $this->getServicesLabels($levels['category'], $levels['subcategory'], $levels['item']);

        Ticket::create([
            'requester_id' => \Auth::id(),
            'creator_id' => \Auth::id(),
            'subject' => $task_label,
            'description' => $task_label,
            'category_id' => $levels['category']->id,
            'subcategory_id' => $levels['subcategory']->id ?? null,
            'item_id' => $levels['item']->id ?? null,
            'status_id' => 1,
            'request_id' => $ticket->id,
            'type' => Ticket::TASK_TYPE,
        ]);
    }

    function postIssue(Document $document,Request $request){
//        @TODO: create the normal ticket and also document ticket
//        @TODO: return to show the ticket
//        @TODO: add type to document requirement
        $requirements = GrRequirements::where('service_type',GrRequirements::SERVICE_TYPE_ISSUE)
            ->where('document_type',$document->type)
            ->get();
//        dd($requirements);

        $ticket = Ticket::create([
            'requester_id' => auth()->id(),
            'creator_id' => auth()->id(),
            'category_id' => 169,
            'status_id' => 1,
            'subject' => 'Issue Document -' . $document->name . ' - '.$document->folder->business_unit->name,
            'description' => 'Issue Document - ' . $document->name . ' - '.$document->folder->business_unit->name,
            'business_unit_id' => $document->folder->business_unit_id,
        ]);

        DocumentTicket::create([
            'ticket_id' => $ticket->id,
            'document_id' => $document->id,
        ]);



        foreach ($requirements as $requirement) {
            TicketRequirements::create([
                'ticket_id' => $ticket->id,
                'requirement_id' => $requirement->id
            ]);
        }

        return redirect()->route('ticket.show',$ticket);
    }
}