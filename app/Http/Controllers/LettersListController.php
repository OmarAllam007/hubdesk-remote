<?php

namespace App\Http\Controllers;

use App\Letter;
use App\LetterField;
use App\LetterGroup;
use App\LetterSubgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use function React\Promise\map;

class LettersListController extends Controller
{
    function subgroups($group)
    {
        return LetterGroup::whereParentGroupId($group)->get(['id', 'name']);
    }

    function letters()
    {

        /** @var Collection $userGroups */
        $userGroups = auth()->user()->groups->pluck('id')->unique();
        $query = Letter::query();

        $query->whereLetterGroupId(\request('group_id'));

        if (\request()->has('subgroup_id')) {
            $query->where('subgroup_id', \request('group_id'));
        }

        return $query->get()->map(function ($letter) use ($userGroups) {
            if ($letter->auth_group_id) {
                return auth()->user()->isAdmin() || $userGroups->contains($letter->auth_group_id) ? $letter : null;
            }
            return $letter;
        })->filter();
    }

    function fields($letter)
    {
        return LetterField::whereLetterId($letter)->get()->map(function ($field) {
            return [
                'id' => $field->id,
                'letter_id' => $field->letter_id,
                'name' => $field->name,
                'type' => LetterField::TYPES[$field->type],
                'options' => $field->options
            ];
        });
    }
}
