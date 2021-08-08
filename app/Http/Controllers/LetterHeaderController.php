<?php

namespace App\Http\Controllers;

use App\BusinessUnit;
use App\LetterHeader;
use Illuminate\Http\Request;

class LetterHeaderController extends Controller
{
    function index()
    {
        $headers = LetterHeader::paginate();
        return view('letters_admin.header.index', compact('headers'));
    }

    function create()
    {
        $businessUnits = BusinessUnit::orderBy('name')->get(['id', 'name', 'code']);

        return view('letters_admin.header.create',
            compact('businessUnits'));
    }

    function store(Request $request)
    {
        $this->validate($request, ['business_unit_id' => 'required', 'path' => 'required']);

        $data = [
            'business_unit_id' => $request->business_unit_id,
        ];

        if ($request->hasFile('path')) {
            $data['path'] = LetterHeader::upload('path', 'headers');
        }

        if ($request->hasFile('stamp_path')) {
            $data['stamp_path'] = LetterHeader::upload('stamp_path', 'stamps');
        }

        LetterHeader::create($data);

        return redirect()->route('letters.header.index');
    }

    function edit(LetterHeader $header)
    {
        $businessUnits = BusinessUnit::orderBy('name')->get(['id', 'name', 'code']);


        return view('letters_admin.header.edit',
            compact('businessUnits', 'header'));
    }

    function update(LetterHeader $header, Request $request)
    {
        $this->validate($request, ['business_unit_id' => 'required']);

        $data = [
            'business_unit_id' => $request->business_unit_id,
        ];

        if ($request->hasFile('path')) {
            $data['path'] = LetterHeader::upload('path', 'headers');
        }

        if ($request->hasFile('stamp_path')) {
            $data['stamp_path'] = LetterHeader::upload('stamp_path', 'stamps');
        }


        $header->update($data);
        return redirect()->route('letters.header.index');
    }
}
