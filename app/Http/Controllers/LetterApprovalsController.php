<?php

namespace App\Http\Controllers;

use App\BusinessUnit;
use App\LetterApproval;
use App\User;
use Illuminate\Http\Request;

class LetterApprovalsController extends Controller
{
    function index()
    {
        $business_units = BusinessUnit::orderBy('name')->paginate(20);
        return view('letters_admin.approvals.index', compact('business_units'));
    }

    function create(BusinessUnit $businessUnit)
    {
        $users = User::whereNotNull('email')
            ->where('is_disabled', 0)
            ->where('employee_id', '<>', 0)
            ->orderBy('name')->get();

        return view('letters_admin.approvals.create',
            compact('businessUnit', 'users'));
    }

    function store(Request $request)
    {
        $this->validate($request, ['role_id' => 'required', 'user_id' => 'required']);

        LetterApproval::create($request->all());

        $businessUnit = BusinessUnit::find($request->business_unit_id);

        return redirect()->route('letters.business-unit.approval.show', $businessUnit);
    }


    function showApprovals(BusinessUnit $businessUnit)
    {
        $levels = $businessUnit->letter_levels()->paginate();
        return view('letters_admin.approvals.show_levels', compact('levels', 'businessUnit'));
    }
}
