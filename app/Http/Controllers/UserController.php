<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function getResetForm()
    {
        return view('user.reset');
    }

    function resetForm(Request $request)
    {
        $this->validate($request, ['old_password' => 'required', 'password' => 'required|confirmed']);

        if (\Hash::check($request->old_password, \Auth::user()->password)) {
            \Auth::user()->update([
                'password' => bcrypt($request->password),
            ]);
            flash(t('Password has been Reset'), 'success');
            return redirect()->route('ticket.index');

        }

        flash(t('Password has not been Reset'), 'error');
        return redirect()->back();
    }
}
