<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Mail\SendSurveyEscalationEmail;
use App\Question;
use App\Survey;
use App\UserSurvey;
use App\Ticket;
use Illuminate\Http\Request;

class SurveyLogController extends Controller
{
    function update(Ticket $ticket, UserSurvey $survey, Request $request)
    {
        $survey->update([
            'comment' => $request->comment,
            'is_submitted' => 1,
        ]);

        if ($question_count = count($request->questions)) {
            $unsatisfied_count = 0;

            foreach ($request->questions as $key => $answer_id) {
                $default_answer = Answer::where('question_id', $key)->where('is_default', 1)->first();
                $answer = Answer::find($answer_id);
                if ($answer->degree < $default_answer->degree) {
                    $unsatisfied_count += 1;
                }

                $survey->survey_answers()->create([
                    'question_id' => $key,
                    'answer_id' => $answer_id
                ]);
            }

            if ($unsatisfied_count > $question_count / 2) {
//                Mail(new );
                \Mail::send(new SendSurveyEscalationEmail($survey));
            }

            return \Redirect::route('ticket.show', $ticket);

        }

    }
}
