<?php

namespace App\Http\Controllers;

use App\Behaviors\AttachmentsTrait;
use App\Category;
use App\Http\Requests\InternshipRequest;
use App\InternshipModel;
use App\Jobs\ApplyBusinessRules;
use App\Jobs\ApplySLA;
use App\Language;
use App\Mail\TrainingTicketCreated;
use App\Mail\TrainingTicketFormCreated;
use App\Subcategory;
use App\Ticket;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class InternshipController extends Controller
{
    use AttachmentsTrait;

    function index()
    {
        return view('internship.en.application_form');
    }

    function ar_index()
    {
        return view('internship.ar.application_form');

    }

    function apply(InternshipRequest $request)
    {
        Ticket::flushEventListeners();

        $category = Category::find(config('internship.category_id'));
        $subCategory = Subcategory::find(config('internship.subcategory_id'));

        $ticket = Ticket::create([
            'category_id' => $category->id,
            'subcategory_id' => $subCategory->id ?? null,
            'requester_id' => config('internship.system_user'),
            'creator_id' => config('internship.system_user'),
            'subject' => $category->name . ' - ' . $subCategory->name,
            'status_id' => 1,
        ]);

        $this->createTicketFields($ticket, $request);

        // send emails
        \Mail::to(request('email'))->send(new TrainingTicketFormCreated($ticket, $request['full_name']));

        $files  = [$request['cv'], $request['letter'],$request['other_documents']];
        $this->uploadTicketAttachments($ticket, array_filter($files));

        // apply business rules & SLA
        dispatch(new ApplyBusinessRules($ticket));
        dispatch(new ApplySLA($ticket));

//        flash('Ticket Created', 'Ticket Created', 'success');
        return redirect()->back()->with('request_send', true);
    }

    private function createTicketFields(Ticket $ticket, InternshipRequest $request)
    {
        $city = $request['city'] == 'Other' ? $request['other_city'] : $request['city'];
        $discipline = $request['discipline'] == 'Other' ? $request['other_degree_name'] : $request['discipline'];

        $ticket->fields()->create(['name' => 'Full Name', 'value' => $request['full_name']]);
        $ticket->fields()->create(['name' => 'Id Number', 'value' => $request['id_number']]);
        $ticket->fields()->create(['name' => 'Gender', 'value' => $request['gender'] == 1 ? 'Male' : 'Female']);
        $ticket->fields()->create(['name' => 'Phone', 'value' => $request['phone']]);
        $ticket->fields()->create(['name' => 'Email', 'value' => $request['email']]);
        $ticket->fields()->create(['name' => 'City of Residence', 'value' => $city]);
        $ticket->fields()->create(['name' => 'Current Address', 'value' => $request['address']]);
        $ticket->fields()->create(['name' => 'Work Preference', 'value' => InternshipModel::$workPreference[$request['work_preference']]]);

        $ticket->fields()->create(['name' => 'College / University Name', 'value' => $request['university_name']]);
        $ticket->fields()->create(['name' => 'Degree Name (Title)', 'value' => $request['degree_name']]);
        $ticket->fields()->create(['name' => 'Academic Major', 'value' => $discipline]);
        $ticket->fields()->create(['name' => 'Expected Year of Graduation', 'value' => $request['expected_graduation_year']]);

//        $ticket->fields()->create(['name' => 'Summer or Co-op?', 'value' => implode(",", $request['type'])]);
        $ticket->fields()->create(['name' => 'Duration', 'value' => $request['duration']]);
        $ticket->fields()->create(['name' => 'Internship Start Date', 'value' => $request['start_date']]);
        $ticket->fields()->create(['name' => 'Internship End Date', 'value' => $request['end_date']]);
        $ticket->fields()->create(['name' => 'University Deadline for Company Approval', 'value' => $request['deadline']]);
        $ticket->fields()->create(['name' => 'Preference for Location / City to do the internship', 'value' => implode(",", $request['pref_city'])]);
        $ticket->fields()->create(['name' => 'Preference for Kifah Group of Companies to do the internship', 'value' => implode(",",$request['pref_company'])]);
        $ticket->fields()->create(['name' => 'Why you want to do Internship training with Al Kifah? ', 'value' => $request['reason']]);
        $ticket->fields()->create(['name' => 'Remarks', 'value' => $request['remarks']]);

    }
}
