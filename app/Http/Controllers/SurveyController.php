<?php

namespace App\Http\Controllers;

use App\Survey;
use App\SurveyLog;
use App\Ticket;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::paginate();

        return view('admin.survey.index', compact('surveys'));
    }

    public function displaySurvey(Ticket $ticket , Survey $survey)
    {

            return view('ticket.survey.show', compact('ticket', 'survey'));

    }

    public function create()
    {
        return view('admin.survey.create');
    }

    public function edit(Survey $survey)
    {
        return view('admin.survey.edit', compact('survey'));
    }

    public function update(Survey $survey, Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        $survey->update($request->all());
        $survey->categories()->sync($request->category_id);

        flash('Survey has been saved', 'success');
        return \Redirect::route('admin.survey.index');

    }

    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required', 'category_id' => 'required']);
        $survey = Survey::create($request->all());
        $survey->categories()->sync($request->category_id);

        return \Redirect::route('admin.survey.index');

    }

    public function show(Survey $survey)
    {
        return view('admin.survey.show', compact('survey'));
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();

        return \Redirect::route('admin.survey.index');
    }
}
