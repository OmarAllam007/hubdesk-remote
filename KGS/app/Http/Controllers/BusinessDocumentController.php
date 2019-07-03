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
use App\Http\Controllers\Controller;
use App\Item;
use App\Subcategory;
use App\Task;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use KGS\DocumentNotification;
use KGS\Requirement;
use Predis\Response\Status;

class BusinessDocumentController extends Controller
{
    function index()
    {
        return view('kgs::business-documents.wizard.index');
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
        Ticket::flushEventListeners();

        $label = $this->getServicesLabels($category, $subcategory, $item);

        $tasks = \request()->get('requirements');
        $tasks_files = \request()->allFiles();

//        $all_checked = count($tasks) == count($tasks_files['requirements']);

        $ticket = Ticket::create([
            'requester_id' => \Auth::id(),
            'creator_id' => \Auth::id(),
            'subject' => $label,
            'description' => $label,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id ?? null,
            'item_id' => $item->id ?? null,
            'status_id' => $this->checkForStatus(),
            'business_unit_id' => $business_unit->id
        ]);

        $this->uploadTicketAttachments($ticket, \request('ticket-attachments'));


        if (count($tasks)) {
            foreach ($tasks as $index => $task) {
                $levels = $this->getServiceLevels($task);

                if (isset($tasks_files['requirements'][$index]['file']) && isset($task['checked'])) {
                    $this->uploadTicketAttachments($ticket, [$tasks_files['requirements'][$index]['file']]);
                } else {
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
            }
        }
        return redirect()->route('ticket.show', $ticket->id);
    }

    private function getServiceLevels($task)
    {
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

        if (count($files)) {
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
        $tasks = \request()->get('requirements');
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
        $this->validate($request,['notifications.*.users'=>['required']]);

        if (count($request->notifications)) {
            foreach ($request->notifications as $key=>$notification) {
                $document = DocumentNotification::where('business_unit_id',$business_unit->id)
                    ->where('level',$key)->first();
                if($document){
                    $document->update([
                        'days'=>$notification['period'],
                       'users'=>$notification['users']
                    ]);
                }
                else{
                    DocumentNotification::create([
                        'business_unit_id'=>$business_unit->id,
                        'level'=>$key,
                        'users'=>$notification['users']
                    ]);
                }

            }
        }
        return redirect()->route('kgs.document.select_category',compact('business_unit'));
    }
}