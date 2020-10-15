<?php

namespace App\Http\Controllers;

use App\ApprovalQuestion;
use App\Attachment;
use App\Http\Requests;
use App\Http\Requests\ApprovalRequest;
use App\Http\Requests\UpdateApprovalRequest;
use App\Jobs\ApplyBusinessRules;
use App\Jobs\ApplySLA;
use App\Jobs\SendApproval;
use App\Jobs\TicketAssigned;
use App\Jobs\UpdateApprovalJob;
use App\Mail\SendNewApproval;
use App\Mail\TicketAssignedMail;
use App\Mail\UpdateApprovalMail;
use App\ReplyTemplate;
use App\Ticket;
use App\TicketApproval;
use App\TicketLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;

class ApprovalController extends Controller
{
    public function send(Ticket $ticket, ApprovalRequest $request)
    {
//        dd($request->all());
        $approvals = collect();

        $files = $request->allFiles();
//        $request->attachments = [];
        $ticketAttachments = [];
        foreach ($request->input('approvals', []) as $i => $approval) {
            $attachments = data_get($files, "approvals.$i.attachments");

            if ($attachments) {
                foreach ($attachments as $attachment) {
                    array_push($ticketAttachments, $attachment);
                }
            }

            $request->request->set('attachments', $ticketAttachments);

            $newApproval = new TicketApproval();
            $newApproval->creator_id = $request->user()->id;
            $newApproval->approver_id = $approval['approver_id'];
            $newApproval->status = 0;

            if ($approval['new_stage']) {
                $newApproval->stage = $i + 1;
//                $newApproval->stage = $ticket->nextApprovalStage();
            } else {
                if ($ticket->hasApprovalStages()) {
                    $newApproval->stage = $ticket->approvals()->max('stage');
                } else {
                    $newApproval->stage = 1;
                }
            }
            $newApproval->content = $approval['description'];

            if ($approval['template_id']) {
                $newApproval->content = ReplyTemplate::find($approval['template_id'])->description;
            }

            /** @var TicketApproval $createdApproval */
            $createdApproval = $ticket->approvals()->create($newApproval->toArray());

            if (isset($approval['questions'])) {
                foreach ($approval['questions'] as $question) {
                    $createdApproval->questions()->create($question);
                }
            }

            $approvals->push($createdApproval->convertToJson());

            if (!empty($request->attachments)) {
                Attachment::uploadFiles(Attachment::TICKET_APPROVAL_TYPE, $createdApproval->id, $request);
            }

        }
        return $approvals;

//        if ($template_id = $request->get('template')) {
//        }

    }

    public function resend(TicketApproval $ticketApproval, Request $request)
    {
        \Mail::to($ticketApproval->approver->email)->queue(new SendNewApproval($ticketApproval));
//        $this->dispatch(new SendNewApproval($ticketApproval));
        TicketLog::approvalLog($ticketApproval, TicketLog::RESEND_APPROVAL);

        flash(t('Approval Info'), t('Approval has been sent successfully'), 'success');

        return redirect()->back();
    }

    public function show(TicketApproval $ticketApproval, Request $request)
    {
        $result = $this->authorizeApproval($ticketApproval, $request);
        if (true !== $result) {
            return $result;
        }

        return view('approval.show', compact('ticketApproval'));
    }

    public function update(TicketApproval $ticketApproval, UpdateApprovalRequest $request)
    {
        $result = $this->authorizeApproval($ticketApproval, $request);

        if (true !== $result) {
            return $result;
        }

        //Triggers updated action in App\Providers\TicketEventsProvider
        $ticketApproval->approval_date = Carbon::now();

//        if ($request->get('questions',[]) && count($request->questions)) {
        foreach ($request->get('questions', []) as $key => $answer) {
            ApprovalQuestion::find($key)->update([
                'answer' => $answer
            ]);
//            }

            $request['status'] = $ticketApproval->approval_questions_status;
        }

        $request['hidden_comment'] = isset($request->hidden_comment);
        $ticketApproval->update($request->all());

        if (!$ticketApproval->ticket->isClosed() && !$ticketApproval->ticket->hasPendingApprovals()) {
            $ticketApproval->ticket->status_id = 3;
            $ticketApproval->ticket->save();
        }

        if ($ticketApproval->ticket->technician_id) {
            \Mail::queue(new UpdateApprovalMail($ticketApproval));
//            $this->dispatch(new UpdateApprovalJob($ticketApproval));
        }

        if ($ticketApproval->status != -1 && $ticketApproval->hasNext()) {
            $approvals = $ticketApproval->getNextStageApprovals();
            foreach ($approvals as $approval) {
                \Mail::to($approval->approver->email)->send(new SendNewApproval($approval));
            }
        }


        if ($ticketApproval->status == 1 && !$ticketApproval->hasNext() && !$ticketApproval->ticket->technician_id) {//KGS
            dispatch(new ApplyBusinessRules($ticketApproval->ticket));
            dispatch(new ApplySLA($ticketApproval->ticket));

            if ($ticketApproval->ticket->technician_id) {
                \Mail::queue(new TicketAssignedMail($ticketApproval->ticket));
            }
        }


        TicketLog::approvalLog($ticketApproval, $ticketApproval->status == TicketApproval::APPROVED ?
            TicketLog::APPROVED : TicketLog::DENIED);

        flash(t('Approval Info'), 'Ticket has been ' . ($ticketApproval->status == TicketApproval::APPROVED ? 'approved' : 'rejected'), 'info');

        return Redirect::route('ticket.show', $ticketApproval->ticket_id);
    }

    public function destroy(TicketApproval $ticketApproval, Request $request)
    {
        if (!can('delete', $ticketApproval) || $ticketApproval->status != TicketApproval::PENDING_APPROVAL) {
//            flash(t('Approval Sent'), t('Action not authorized'), 'error');
//            return Redirect::back();
            return;
        }

        $ticketApproval->delete();

        TicketLog::approvalLog($ticketApproval, TicketLog::DELETE_APPROVAL);

        if (!$ticketApproval->ticket->hasPendingApprovals()) {
            $ticketApproval->ticket->update(['status_id' => 3]);
        }

//        flash(t('Approval Info'), 'Approval has been deleted', 'info');
//        return Redirect::route('ticket.show', $ticketApproval->ticket_id);
    }

    /**
     * @param TicketApproval $ticketApproval
     * @param Request $request
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    protected function authorizeApproval(TicketApproval $ticketApproval, Request $request)
    {
        if ($ticketApproval->approver_id != $request->user()->id) {

            return Redirect::route('ticket.show', $ticketApproval->ticket_id);
        }

        if ($ticketApproval->status != TicketApproval::PENDING_APPROVAL) {

            flash(t('Approval Info'), t('You already took action for this approval'), 'info');
            return Redirect::route('ticket.show', $ticketApproval->ticket_id);
        }

        if ($ticketApproval->ticket->isClosed()) {
            flash(t('Approval Info'), t('The ticket has been closed'), 'info');
            return Redirect::route('ticket.show', $ticketApproval->ticket_id);
        }

        return true;
    }
}
