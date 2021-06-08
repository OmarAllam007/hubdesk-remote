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
        $approvals = collect();
        $files = $request->allFiles();
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
            $newApproval->stage = isset($approval['stage']) ? $approval['stage'] : $ticket->approvals()->max('stage');

            if ($approval['new_stage']) {
                $newApproval->stage = $ticket->nextApprovalStage();
            } elseif (!$ticket->hasApprovalStages()) {
                $newApproval->stage = 1;
            }


            if ($template_id = $request->get('template')) {
                $approval["content"] = ReplyTemplate::find($template_id)->description;
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
    }

    public function resend(TicketApproval $ticketApproval, Request $request)
    {
        \Mail::to($ticketApproval->approver->email)->queue(new SendNewApproval($ticketApproval));
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
            ApprovalQuestion::find($answer['question_id'])->update([
                'answer' => $answer['answer']
            ]);

        }

        $request['status'] = count($request->get('questions')) ? $ticketApproval->approval_questions_status : $request->get('status');
        $request['hidden_comment'] = $request->hide_the_comment;

        $ticketApproval->update($request->all());

        if (!$ticketApproval->ticket->isClosed() && !$ticketApproval->ticket->hasPendingApprovals()) {
            $ticketApproval->ticket->status_id = 3;
            $ticketApproval->ticket->save();
        }

        if ($ticketApproval->ticket->technician_id) {
            \Mail::queue(new UpdateApprovalMail($ticketApproval));
        }

        if ($ticketApproval->status != -1 && !$ticketApproval->hasPendingOnSameStage() && $ticketApproval->hasNext()) {
            $approvals = $ticketApproval->getNextStageApprovals();
            foreach ($approvals as $approval) {
                \Mail::to($approval->approver->email)->queue(new SendNewApproval($approval));
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

        return response()->json(['message' => 'Approval has been updated successfully', 'approval_status' => ($ticketApproval->status == TicketApproval::APPROVED ? 'approved' : 'rejected')]);
    }

    public function destroy(TicketApproval $ticketApproval, Request $request)
    {
        if (!can('delete', $ticketApproval) || $ticketApproval->status != TicketApproval::PENDING_APPROVAL) {
            return;
        }

        $ticketApproval->delete();

        TicketLog::approvalLog($ticketApproval, TicketLog::DELETE_APPROVAL);

        if (!$ticketApproval->ticket->hasPendingApprovals()) {
            $ticketApproval->ticket->update(['status_id' => 3]);
        }

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
