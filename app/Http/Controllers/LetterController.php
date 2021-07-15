<?php

namespace App\Http\Controllers;

use App\Letter;
use App\LetterGroup;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    function index()
    {
        $letters = Letter::paginate();
        return view('letters_admin.letter.index', compact('letters'));
    }

    function create()
    {

        return view('letters_admin.letter.create');
    }

    function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        Letter::create($request->all());

        return redirect()->route('letters.letter.index');
    }

    function edit(Letter $letter)
    {
        return view('letters_admin.letter.edit', compact('letter'));
    }

    function update(Letter $letter, Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        $letter->update($request->all());

        return redirect()->route('letters.letter.index');
    }

    function destroy(Letter $letter)
    {


    }
}
