<?php

namespace App\Http\Controllers;

use App\Helpers\SapApi;
use App\Rules\ResetPassword;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
                'password_reset' => false,
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
//        return view('errors.maintenance');

        if (!auth()->user()->employee_id) {
            return redirect('/');
        }
        $userInfoAPI = new SapApi(auth()->user());
        $salarySlipPaths = $userInfoAPI->getSalarySlip();

        if (!$salarySlipPaths) {
            return;
        }

        $paths = [];

        foreach ($salarySlipPaths as $path){
            $basename = str_replace('+', ' ', urlencode(basename($path)));
            $dirname = dirname($path);
            $path = $dirname . '/' . $basename;
            $paths[] = url('/storage/' . $path);
        }


        return view('user.employee_information', ['index' => \request('index'),'paths'=>$paths]);
    }

    function getSalarySlipPdf($path)
    {
//        dd($path);
//        $userInfoAPI = new SapApi(auth()->user());
//        $salarySlip = $userInfoAPI->getSalarySlip();
//
//        if (!$salarySlip) {
//            return;
//        }
//
//        $index = \request('index') ?? count($salarySlip);

        $path = storage_path("app/public/salary_slip/{$path}");


        return response()->download($path,
            'salary.pdf', ['Content-Type' => 'application/pdf'], 'inline')
            ->deleteFileAfterSend();
    }


}
