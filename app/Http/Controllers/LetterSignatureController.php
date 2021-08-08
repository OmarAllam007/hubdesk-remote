<?php

namespace App\Http\Controllers;

use App\BusinessUnit;
use App\Letter;
use App\LetterSignature;
use App\User;
use Illuminate\Http\Request;

class LetterSignatureController extends Controller
{
    function index()
    {
        $signatures = LetterSignature::paginate();
        return view('letters_admin.signature.index', compact('signatures'));
    }

    function create()
    {
        $users = User::approvers()->orderBy('name')->get(['id', 'name', 'email', 'employee_id']);
        $businessUnits = BusinessUnit::orderBy('name')->get(['id', 'name', 'code']);
        $letters = Letter::with('group')->orderBy('name')->get();

        return view('letters_admin.signature.create',
            compact('users', 'businessUnits', 'letters'));
    }

    function store(Request $request)
    {
        $this->validate($request, ['letter_id' => 'required', 'user_id' => 'required', 'business_unit_id' => 'required']);
        LetterSignature::create($request->all());

        return redirect()->route('letters.signature.index');
    }

    function edit(LetterSignature $signature)
    {
        $users = User::approvers()->orderBy('name')->get(['id', 'name', 'email', 'employee_id']);
        $businessUnits = BusinessUnit::orderBy('name')->get(['id', 'name', 'code']);
        $letters = Letter::with('group')->orderBy('name')->get();


        return view('letters_admin.signature.edit',
            compact('users', 'businessUnits', 'letters', 'signature'));
    }

    function update(LetterSignature $signature, Request $request)
    {
        $this->validate($request, ['letter_id' => 'required', 'user_id' => 'required', 'business_unit_id' => 'required']);
        $signature->update($request->all());

        return redirect()->route('letters.signature.index');
    }
}
