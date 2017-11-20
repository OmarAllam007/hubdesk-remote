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

        return view('admin.question.index', compact('questions'));
    }

    public function create()
    {
        return view('admin.question.create');
    }

    public function edit(Question $question,Survey $survey)
    {
        return view('admin.question.edit', compact('question','survey'));
    }

    public function update(Question $question, Request $request)
    {
        $this->validate($request, ['description' => 'required']);
        $survey->update($request->all());

        flash('Question has been saved', 'success');
        return \Redirect::route('admin.question.index');

    }

    public function store(Request $request)
    {
        $this->validate($request, ['description' => 'required']);
        Question::create(['description' => $request->description]);

        return \Redirect::route('admin.question.index');

    }

    public function show(Survey $survey , Question $question)
    {
        return view('admin.question.show', compact('question','survey'));
    }
}
