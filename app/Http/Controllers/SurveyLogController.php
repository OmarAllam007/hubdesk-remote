<?php

namespace App\Http\Controllers;

use App\Survey;
use App\SurveyLog;
use App\Ticket;
use Illuminate\Http\Request;

class SurveyLogController extends Controller
{
    function update(Ticket $ticket, Survey $survey ,request $request){
        if(count($request->questions)){
            foreach ($request->questions as $key=>$answer){
                 SurveyLog::create(['ticket_id'=>$ticket->id,
                    'survey_id'=>$survey->id,
                    'question_id'=>$key,
                    'answer_id'=>$answer,'comment'=>$request->comment ?? '']);
            }
        }
        return \Redirect::route('ticket.show',$ticket);
    }
}
