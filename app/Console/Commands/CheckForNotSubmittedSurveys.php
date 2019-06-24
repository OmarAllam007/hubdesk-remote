<?php

namespace App\Console\Commands;

use App\Mail\SendSurveyEmail;
use App\Survey;
use App\TicketLog;
use App\UserSurvey;
use App\UserSurveyAnswer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckForNotSubmittedSurveys extends Command
{

    protected $signature = 'surveys:check';

    protected $description = 'Check for not Submitted Surveys';

    protected $now;

    public function __construct()
    {
        parent::__construct();
        Carbon::setWeekendDays([Carbon::FRIDAY, Carbon::SATURDAY]);
        $this->now = Carbon::now();
    }


    public function handle()
    {
        $notSubmittedSurveys = UserSurvey::where('is_submitted', 0)
            ->whereDate('updated_at', '<', $this->now)->get();

        if($notSubmittedSurveys->count()){

            foreach ($notSubmittedSurveys as $survey) {
                if ($survey->notified == 1) {
                    \Mail::send(new SendSurveyEmail($survey));

                    $survey->update([
                        'notified' => 2
                    ]);

                } elseif ($survey->notified == 2) {
                    //update notified to 3
                    // make the survey as satiesfied
                    $survey->update([
                        'notified' => 3,
                        'is_submitted' => 1
                    ]);

                    /** @var Survey $actual_survey */
                    $questions = $survey->survey->questions;

                    foreach ($questions as $question) {
                        $survey->survey_answers()->create([
                            'question_id' => $question->id,
                            'answer_id' => $question->default_answer->id
                        ]);
                    }

                }

                TicketLog::addReminderOnSurvey($survey->ticket);
            }
        }

    }
}
