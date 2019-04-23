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

        return view('admin.answer.show', compact('question', 'answers'));
    }

    public function create(Question $question)
    {
        return view('admin.answers.create', compact('question'));
    }

    public function edit($answer)
    {
        $answer = Answer::find($answer);

        return view('admin.answers.edit', compact('answer'));
    }

    public function update(Answer $answer, Request $request)
    {
        $this->validate($request, ['description' => 'required', 'degree' => 'numeric|min:0|max:5']);

        $answer->update(['description' => $request->description, 'degree' => $request->degree]);

        flash('Answer has been saved', 'success');

        return \Redirect::route('admin.question.show', $answer->question);

    }

    public function store($question, Request $request)
    {
        $this->validate($request, ['description' => 'required', 'degree' => 'numeric|min:0|max:5']);

        $question = Question::find($question);
        Answer::create(['description' => $request->description, 'question_id' => $question->id, 'degree' => $request->degree]);

        return \Redirect::route('admin.question.show', compact('question'));

    }

    public function show(Question $question, Answer $answer)
    {
        return view('admin.answer.show', compact('answer', 'question'));
    }

    public function destroy(Answer $answer )
    {
        $question = Question::find(\request()->get('question'));
        $answer->delete();

        return \Redirect::route('admin.question.show',compact('question'));
    }
}
