<?php

namespace App\Http\Controllers;

use App\Letter;
use App\LetterGroup;
use App\LetterSubgroup;
use Illuminate\Http\Request;

class LettersListController extends Controller
{
    function subgroups($group)
    {
        return LetterGroup::whereParentGroupId($group)->get(['id', 'name']);
    }

    function letters()
    {
        $query = Letter::query();

        $query->whereLetterGroupId(\request('group_id'));

        if (\request()->has('subgroup_id')) {
            $query->where('subgroup_id', \request('group_id'));
        }

        return $query->get();
    }
}
