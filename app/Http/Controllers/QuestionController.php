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
        $this->validate($request, ['description' => 'required']);

        $question->update(['description'=>$request->description]);

        flash('Question has been saved', 'success');

        return \Redirect::route('admin.survey.index');

    }

    public function store($survey,Request $request)
    {
        $survey = Survey::find($survey);
        $this->validate($request, ['description' => 'required']);

        $q = Question::create(['description' => $request->description]);
        $survey->questions()->syncWithoutDetaching($q->id);

        return \Redirect::route('admin.survey.show',compact('survey'));

    }

    public function show(Survey $survey , Question $question)
    {
        return view('admin.question.show', compact('question','survey'));
    }
}
