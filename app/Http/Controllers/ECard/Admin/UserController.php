<?php

namespace App\Http\Controllers\ECard\Admin;

use App\BusinessCardUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function create()
    {
        return view('e-card.admin.user.create');
    }

    public function store(Request $request)
    {
        $user = new BusinessCardUser($request->except('_token'));

        if ($request->hasFile('image_url')) {
            $user['image_url'] = $user->upload('image_url');
        }

        $user->save();

        flash(t('User Info'), t('User has been saved'), 'success');

        return \Redirect::route('admin.user.index');
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    public function update(User $user, AdminUserRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('signature')) {
            $data['signature'] = $user->upload('signature');
        }

        if ($request->get('password') || $request->get('default_password')) {
            $data['password'] = bcrypt($data['password'] ?? env('DEFAULT_PASS'));

            if ($request->get('default_password')) {
                $data['password_reset'] = true;
                $data['last_reset_password_date'] = Carbon::now();
            }

        } else {
            unset($data['password'], $data['password_confirmation']);
        }

        $data['is_disabled'] = isset($request->is_disabled) ? 1 : 0;

        $user->update($data);

        flash(t('User Info'), t('User has been saved'), 'success');

        return \Redirect::route('admin.user.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        flash(t('User Info'), t('User has been deleted'), 'success');

        return \Redirect::route('admin.user.index');
    }
}
