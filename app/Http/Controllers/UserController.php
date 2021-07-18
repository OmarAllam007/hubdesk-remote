<?php

namespace App\Http\Controllers;

use App\Helpers\SapApi;
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

            flash(t('Reset Password'), t('Password has been Reset'), 'success');
            return redirect()->route('ticket.index');
        }

        flash(t('Reset Password'), t('Password has not been Reset'), 'error');
        return redirect()->back();
    }

    function getUserInfo($requester)
    {
        $requester = User::find($requester);
        $requester['business_unit_name'] = $requester->business_unit->name ?? 'Not Assigned';
        $requester['department_name'] = $requester->department->name ?? 'Not Assigned';

        return $requester;
    }

    function getUserInformation()
    {
        if (!auth()->user()->employee_id || !(env('SALARY_SLIP_USERS') && in_array(auth()->id(),explode(',',env('SALARY_SLIP_USERS'))))) {
            return redirect('/');
        }

        return view('user.employee_information');
    }

    function getSalarySlipPdf()
    {

        $userInfoAPI = new SapApi(auth()->user());
        $salarySlip = $userInfoAPI->getSalarySlip();

        if(!$salarySlip){
            return;
        }
        $path = storage_path("app/public/{$salarySlip}");

        return response()->download($path,
            'salary.pdf', ['Content-Type' => 'application/pdf'], 'inline')
            ->deleteFileAfterSend();
    }


}
