<?php


namespace App\Listeners\Letters;


use App\Letter;
use App\LetterTaskAssignment;
use App\Mail\LetterRequestForFees;
use App\Mail\NewTaskMail;
use App\Mail\ReplyTicketMail;
use App\Mail\SendSurveyEmail;
use App\Ticket;
use App\TicketLog;
use App\TicketReply;
use App\UserSurvey;
use Carbon\Carbon;

class LetterTicketParentListener
{
    protected function closeLetterTicket($letterTicket)
    {
        Ticket::flushEventListeners();
        TicketReply::flushEventListeners();
        TicketLog::flushEventListeners();

        $letterTicket->ticket->update([
            'status_id' => 8,
            'closed_date' => Carbon::now()->toDateTimeString()
        ]);

        $reply = $letterTicket->ticket->replies()->create([
            'user_id' => config('letters.system_user'),
            'status_id' => 8,
            'content' => config('letters.close_reply_content'),
        ]);

        TicketLog::addReply($reply);
        TicketLog::makeLog($letterTicket->ticket, TicketLog::UPDATED_TYPE, config('letters.system_user'));

        $this->sendEmail($letterTicket, $reply);
    }


    protected function createKGSTask($letterTicket)
    {
        Ticket::flushEventListeners();
        TicketReply::flushEventListeners();
        $letterTicket->ticket->update([
            'status_id' => 4
        ]);

        $requesterBusinessUnit = $letterTicket->ticket->requester->business_unit;

        $assignedTo = LetterTaskAssignment::where('business_unit_id', $requesterBusinessUnit->id)->first();
        $technician = $assignedTo ? $assignedTo->user_id : config('letters.kgs_technician');

        $content = $this->transferReply($letterTicket, $requesterBusinessUnit->name,
            $requesterBusinessUnit->iban ?? "SA30 4500 0000 0480 7025 4013", $requesterBusinessUnit);

        if (in_array($letterTicket->ticket->requester->business_unit_id, [4, 39, 41, 42, 46])) {
            $technician = $assignedTo ? $assignedTo->user_id : config('letters.kcc_technician');
            $content = '<p>On Hold</p>';
        }

        // @TODO: send email to HR if discount from salary

        $ticket = Ticket::create([
            'subject' => $letterTicket->ticket->subject,
            'description' => $letterTicket->ticket->subject,
            'type' => Ticket::TASK_TYPE,
            'request_id' => $letterTicket->ticket->id,
            'requester_id' => config('letters.system_user'),
            'creator_id' => config('letters.system_user'),
            'status_id' => 1,
            'category_id' => $letterTicket->ticket->category_id,
            'subcategory_id' => $letterTicket->ticket->subcategory_id,
            'item_id' => $letterTicket->ticket->item_id,
            'group_id' => config('letters.kgs_group'),
            'technician_id' => $technician,
        ]);

//        new update to make it not for contracting companies

        \Mail::queue(new NewTaskMail($ticket));

        if (!in_array($letterTicket->ticket->requester->business_unit_id, [4, 39, 42, 46])) {
            $reply = $letterTicket->ticket->replies()->create([
                'user_id' => config('letters.system_user'),
                'status_id' => 4,
                'content' => $content,
            ]);

            if ($letterTicket->payment_type == Letter::TRANSFER_TO_BANK) {

                \Mail::to($letterTicket->ticket->requester->email)
                    ->queue(new LetterRequestForFees($letterTicket, $reply,$content));
            }
        }


    }


    protected function sendEmail($letterTicket, $reply)
    {
        $requester = $letterTicket->ticket->requester->email;

        if ($letterTicket->ticket->requester->email) {
            \Mail::to($requester)->queue(new ReplyTicketMail($reply));

            $this->sendSurvey($letterTicket);
        }

    }

    protected function sendSurvey($letterTicket)
    {
        $survey = UserSurvey::create([
            'ticket_id' => $letterTicket->ticket->id,
            'survey_id' => $letterTicket->ticket->category->survey->first()->id,
            'comment' => '',
            'is_submitted' => 0,
            'notified' => 1
        ]);

        if ($survey->ticket->requester->email) {
            \Mail::queue(new SendSurveyEmail($survey));
        }
        TicketLog::addReminderOnSurvey($letterTicket->ticket);
    }

    function transferReply($letterTicket, $businessUnit, $IBAN, $requesterBusinessUnit)
    {
        if ($letterTicket->payment_type == Letter::DEDUCTION_FROM_SALARY) {
            return '<p>On Hold</p>';
        }

        $arBusinessUnit = t($businessUnit);
        $enBankName = $requesterBusinessUnit->bank_name1 ?? 'SABB';
        $arBankName = $requesterBusinessUnit->bank_name1 ?? 'بنك ساب';

        $reply = "<p style='text-align: center;direction:rtl'>عزيزي مقدم / ـة الطلب</p>";
        $reply .= "<p style='text-align: center;direction:rtl'>تحية طيبة وبعد ،</p>";
        $reply .= "<p style='text-align: center;direction:rtl'>يرجى تحويل رسوم تصديق الخطاب بمبلغ : 35 ريال على رقم الحساب البنكي رقم : $IBAN </p>";
        $reply .= "<p style='text-align: center;direction:rtl'>أسم البنك / $arBankName </p>";
        $reply .= "<p style='text-align: center;direction:rtl'>أسم المستفيد / $arBusinessUnit</p>";
        $reply .= "<p style='text-align: center;direction:rtl'>ومن ثم تزويدنا بنسخة من إيصال التحويل حتى نتمكن من إكمال إجراءات عملية التصديق.</p>";
        $reply .= "<p style='text-align: center;direction:rtl'>شكراً</p>";
        $reply .="<br>";
        $reply .= "<p style='text-align: center;'>Dear Requester,</p>";
        $reply .= "<p style='text-align: center;'>Greeting,</p>";
        $reply .= "<p style='text-align: center;'>Please transfer the attestation fees : 35 riyals to be transferred to the bank account number: $IBAN</p>";
        $reply .= "<p style='text-align: center;'>Bank Name / $enBankName</p>";
        $reply .= "<p style='text-align: center;'>Company / $businessUnit</p>";
        $reply .= "<p style='text-align: center;'>then provide us with a copy of the transfer receipt, based on that we can complete the attestation process.</p>";
        $reply .= "<p style='text-align: center;'>Thank you</p>";

        return $reply;
    }
}