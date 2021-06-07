<?php

namespace App\Http\Controllers;

use App\Rules\ResetPassword;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function getResetForm()
    {
        return view('user.reset');
    }

    function resetForm(Request $request)
    {
        $this->validate($request, ['old_password' => ['required', new ResetPassword], 'password' => 'required|min:5|confirmed']);

        if (\Hash::check($request->old_password, \Auth::user()->password)) {
            \Auth::user()->update([
                'password' => bcrypt($request->password),
                'password_reset'=> false,
            ]);

            flash(t('Reset Password'),t('Password has been Reset'), 'success');
            return redirect()->route('ticket.index');
        }

        flash(t('Reset Password'),t('Password has not been Reset'), 'error');
        return redirect()->back();
    }

    function getUserInfo($requester)
    {
        $requester = User::find($requester);
        $requester['business_unit_name'] = $requester->business_unit->name ?? 'Not Assigned';
        $requester['department_name'] = $requester->department->name ?? 'Not Assigned';

        return $requester;
    }
}
