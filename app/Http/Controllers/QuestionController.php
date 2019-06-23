<?php

namespace App\Http\Controllers;

use App\Question;
use App\Survey;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Survey $survey)
    {
        $questions = $survey->questions;

        return view('admin.survey.show', compact('survey','questions'));
    }

    public function create(Survey $survey)
    {
        return view('admin.question.create',compact('survey'));
    }

    public function edit($question)
    {
        $question = Question::find($question);

        return view('admin.question.edit', compact('question'));
    }

    public function update(Question $question,Request $request)
    {
        $this->validate($request, ['description' => 'required','degree'=>'numeric']);

        $question->update($request->all());

        flash('Question has been saved', 'success');

        return \Redirect::route('admin.survey.index');

    }

    public function store($survey,Request $request)
    {
        $this->validate($request, ['description' => 'required','degree'=>'numeric']);
        $survey = Survey::find($survey);

        $q = Question::create(['description' => $request->description,'survey_id'=>$survey->id,'degree'=>$request->degree ?? null ]);

        return \Redirect::route('admin.survey.show',compact('survey'));

    }

    public function show($question)
    {
        $question = Question::find($question);
        return view('admin.question.show', compact('question'));
    }

    public function destroy(Question $question,Survey $survey){

        $question->delete();

        return \Redirect::route('admin.survey.show',compact('survey'));
    }
}
