<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    protected $rules = ['name' => 'required', 'type' => 'required'];

    public function index()
    {
        $groups = Group::orderBy('name')->quickSearch()->paginate();
        return view('admin.group.index', compact('groups'));
    }

    public function userGroups()
    {
        $groups = Group::whereType(1)->orderBy('name')->quickSearch()->paginate();
        return view('admin.group.user_group_index', compact('groups'));
    }
    public function create()
    {
        return view('admin.group.create');
    }

    public function store(Request $request)
    {
        $this->validates($request);
        $data = $request->all();
        $data['is_disabled'] = isset($request->is_disabled) ? 1 : 0;

        $group = Group::create($data);

        if ($request->supervisors) {
            $group->supervisors()->sync($request->supervisors);
        }

        if($request->users){
            $group->users()->sync($request->users);
        }

        flash(t("Group Info"),t('Group has been saved'), 'success');

        return \Redirect::route('admin.group.index');
    }

    public function show(Group $group)
    {
        return view('admin.group.show', compact('group'));
    }

    public function edit(Group $group)
    {
        return view('admin.group.edit', compact('group'));
    }

    public function update(Group $group, Request $request)
    {
        $this->validates($request);

        if ($request->supervisors) {
            $group->supervisors()->sync($request->supervisors);
        }
        if($request->users){
            $group->users()->sync($request->users);
        }
        $data = $request->all();
        $data['is_disabled'] = isset($request->is_disabled) ? 1 : 0;
        $group->update($data);

        flash(t("Group Info"),t('Group has been saved'), 'success');

        return \Redirect::route('admin.group.index');
    }

    public function destroy(Group $group)
    {
        $group->delete();

        flash(t("Group Info"),t('Group has been deleted'), 'success');

        return \Redirect::route('admin.group.index');
    }

    public function addUser(Group $group, Request $request)
    {
        $this->validate($request, ['user_id' => 'required|exists:users,id']);

        $group->users()->attach($request->get('user_id'));

        flash(t("Group Info"),t('User has been added to group'), 'success');

        return \Redirect::route('admin.group.show', $group);
    }

    public function removeUser(Group $group, User $user)
    {
        $group->users()->detach($user->id);

        flash(t("Group Info"),t('User has been removed from group'), 'success');

        return \Redirect::route('admin.group.show', $group);
    }
}
