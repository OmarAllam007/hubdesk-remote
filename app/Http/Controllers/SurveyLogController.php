<?php

namespace App\Http\Controllers;

use App\Survey;
use App\UserSurvey;
use App\Ticket;
use Illuminate\Http\Request;

class SurveyLogController extends Controller
{
    function update(Ticket $ticket, Survey $survey, request $request)
    {
        $log = UserSurvey::notSubmitted()->where('ticket_id', $ticket->id)->where('survey_id', $survey->id)->first();


        if ($log) {
            $log->update([
                'comment' => $request->comment,
                'is_submitted' => 1,
            ]);

            if (count($request->questions)) {
                foreach ($request->questions as $key => $answer) {
                    $log->survey_answers()->create([
                        'question_id' => $key,
                        'answer_id' => $answer
                    ]);
                }
            }
        }


        return \Redirect::route('ticket.show', $ticket);
    }
}
