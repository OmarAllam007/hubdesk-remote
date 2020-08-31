<?php

namespace App\Http\Controllers;

use App\ApprovalQuestion;
use App\Http\Requests;
use App\Http\Requests\ApprovalRequest;
use App\Http\Requests\UpdateApprovalRequest;
use App\Jobs\ApplyBusinessRules;
use App\Jobs\ApplySLA;
use App\Jobs\SendApproval;
use App\Jobs\UpdateApprovalJob;
use App\Mail\SendNewApproval;
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
        //Triggers created action in App\Providers\TicketEventsProvider
        $approval = new TicketApproval($request->all());
        $approval->creator_id = $request->user()->id;
        $approval->status = 0;
        if ($request->get('add_stage')) {
            $approval->stage = $ticket->nextApprovalStage();
        } elseif (!$ticket->hasApprovalStages()) {
            $approval->stage = 1;
        }
        if ($template_id = $request->get('template')) {
            $approval["content"] = ReplyTemplate::find($template_id)->description;
        }

        $ticket->approvals()->save($approval);

        foreach ($request->get('questions', []) as $question) {
            $approval->questions()->create($question);
        }

        flash(t('Approval Info'), t('Approval has been sent successfully'), 'success');

        return redirect()->back();
    }

    public function resend(TicketApproval $ticketApproval, Request $request)
    {
        \Mail::to($ticketApproval->approver->email)->send(new SendNewApproval($ticketApproval));
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


        if ($request->questions && count($request->questions)) {
            foreach ($request->get('questions', []) as $key => $answer) {
                ApprovalQuestion::find($key)->update([
                    'answer' => $answer
                ]);
            }

            $request['status'] = $ticketApproval->approval_questions_status;
        }

        $request['hidden_comment'] = isset($request->hidden_comment) ? true : false;
        $ticketApproval->update($request->all());

        if (!$ticketApproval->ticket->isClosed() && !$ticketApproval->ticket->hasPendingApprovals()) {
            $ticketApproval->ticket->status_id = 3;
            $ticketApproval->ticket->save();
        }

        if ($ticketApproval->ticket->technician_id) {
            $this->dispatch(new UpdateApprovalJob($ticketApproval));
        }

        if ($ticketApproval->status != -1 && $ticketApproval->hasNext()) {
            $approvals = $ticketApproval->getNextStageApprovals();
            foreach ($approvals as $approval) {
                \Mail::to($approval->approver->email)->send(new SendNewApproval($approval));
            }
        }


        if ($ticketApproval->status == 1 && !$ticketApproval->hasNext() && !$ticketApproval->ticket->technician_id) {
            dispatch(new ApplyBusinessRules($ticketApproval->ticket));
            dispatch(new ApplySLA($ticketApproval->ticket));
        }


        TicketLog::approvalLog($ticketApproval, $ticketApproval->status == TicketApproval::APPROVED ?
            TicketLog::APPROVED : TicketLog::DENIED);

        flash(t('Approval Info'), 'Ticket has been ' . ($ticketApproval->status == TicketApproval::APPROVED ? 'approved' : 'rejected'), 'info');

        return Redirect::route('ticket.show', $ticketApproval->ticket_id);
    }

    public function destroy(TicketApproval $ticketApproval, Request $request)
    {
        if (!can('delete', $ticketApproval) || $ticketApproval->status != TicketApproval::PENDING_APPROVAL) {

            flash(t('Approval Sent'), t('Action not authorized'), 'error');

            return Redirect::back();
        }

        $ticketApproval->delete();

        TicketLog::approvalLog($ticketApproval, TicketLog::DELETE_APPROVAL);

        if (!$ticketApproval->ticket->hasPendingApprovals()) {
            $ticketApproval->ticket->update(['status_id' => 3]);
        }

        flash(t('Approval Info'), 'Approval has been deleted', 'info');
        return Redirect::route('ticket.show', $ticketApproval->ticket_id);
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
