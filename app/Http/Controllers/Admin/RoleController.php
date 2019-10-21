<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\BusinessUnit;


class RoleController extends Controller
{
    protected $rules = ['name' => 'required'];

    public function index()
    {
        $roles = Role::orderBy('name')->quickSearch()->paginate();
        return view('admin.role.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.role.create');
    }

    public function store(Request $request)
    {
        $this->validates($request);

        $role = Role::create($request->all());

        if ($request->users) {
            $role->users()->sync($request->users);
        }

        flash(t('Role Info'), t('role has been saved'), 'success');

        return \Redirect::route('admin.role.index');
    }

    public function show(Role $role)
    {
        return view('admin.role.show', compact('role'));
    }

    public function edit(Role $role)
    {
        return view('admin.role.edit', compact('role'));
    }

    public function update(Role $role, Request $request)
    {
        $this->validates($request);

        if ($request->users) {
            $role->users()->sync($request->users);
        }

        $role->update($request->all());

        flash(t('Role Info'), t('role has been saved'), 'success');

        return \Redirect::route('admin.role.index');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        flash(t('Role Info'), t('Role has been deleted'), 'success');

        return \Redirect::route('admin.role.index');
    }

    public function addUser(Role $role, Request $request)
    {
        $this->validate($request, ['user_id' => 'required|exists:users,id']);

        $role->users()->attach($request->get('user_id'));

        flash(t('Role Info'), t('User has been added to role'), 'success');

        return \Redirect::route('admin.role.show', $role);
    }

    public function removeUser(Role $role, User $user)
    {
        $role->users()->detach($user->id);

        flash(t('Role Info'), t('User has been removed from role'), 'success');

        return \Redirect::route('admin.role.show', $role);
    }
} 
