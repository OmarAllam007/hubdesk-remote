<?php

namespace App\Http\Controllers;

use App\LetterField;
use Illuminate\Http\Request;

class LetterFieldController extends Controller
{
    const validation = ['name' => 'required', 'letter_id' => 'required', 'type' => 'required'];

    function index()
    {
        $fields = LetterField::orderBy('name')->paginate();
        return view('letters_admin.letter_field.index', compact('fields'));
    }

    function create()
    {
        return view('letters_admin.letter_field.create');
    }

    function store(Request $request)
    {
        $this->validate($request, self::validation);

        LetterField::create($request->all());

        return redirect()->route('letters.letter-field.index');
    }

    function edit(LetterField $letter_field)
    {
        return view('letters_admin.letter_field.edit', compact('letter_field'));
    }

    function update(LetterField $letter_field, Request $request)
    {
        $letter_field->update($request->all());
        return redirect()->route('letters.letter-field.index');
    }

    function show(LetterField $letter_field)
    {
        return view('letters_admin.letter_field.show', compact('letter_field'));
    }

    function destroy(LetterField $letter_field)
    {
        $letter_field->delete();
        return redirect()->route('letters.letter-field.index');
    }
}
