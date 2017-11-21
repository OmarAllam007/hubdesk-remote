<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function index(Question $question)
    {
        $answers = $question->answers;

        return view('admin.answer.show', compact('question','answers'));
    }

    public function create(Question $question)
    {
        return view('admin.answer.create',compact('question'));
    }

    public function edit($answer)
    {
        $answer = Answer::find($answer);

        return view('admin.answer.edit', compact('answer'));
    }

    public function update(Answer $answer,Request $request)
    {
        $this->validate($request, ['description' => 'required']);

        $answer->update(['description'=>$request->description]);

        flash('Answer has been saved', 'success');

        return \Redirect::route('admin.question.index');

    }

    public function store($question,Request $request)
    {
        $question = Question::find($question);
        $this->validate($request, ['description' => 'required']);

        $q = Answer::create(['description' => $request->description]);
        $question->answers()->syncWithoutDetaching($q->id);

        return \Redirect::route('admin.question.show',compact('question'));

    }

    public function show(Question $question , Answer $answer)
    {
        return view('admin.answer.show', compact('answer','question'));
    }
}
