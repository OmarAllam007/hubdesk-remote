<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use App\Jobs\LdapImportUsers;
use App\Jobs\UploadUsersJob;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->quickSearch()->paginate();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(AdminUserRequest $request)
    {
        $user = new User($request->all());
        $user->password = bcrypt($request->get('password') ?? env('DEFAULT_PASS'));

        $user->save();

        flash(t('User has been saved'), 'success');

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

        if ($request->get('password') || $request->get('default_password')) {
            $data['password'] = bcrypt($data['password'] ?? env('DEFAULT_PASS'));
        } else {
            unset($data['password'], $data['password_confirmation']);
        }

        $user->update($data);

        flash(t('User has been saved'), 'success');

        return \Redirect::route('admin.user.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        flash(t('User has been deleted'), 'success');

        return \Redirect::route('admin.user.index');
    }

    public function ldapImport(Request $request)
    {
        $this->validate($request, ['login' => 'required']);

        $users = preg_split('/\r?\n/', $request->login);
        $this->dispatch(new LdapImportUsers($users));

        $msg = 'Users has been imported';
        if ($request->isJson() || $request->wantsJson()) {
            return ['ok' => true, 'message' => $msg];
        }
        flash($msg, 'success');

        return \Redirect::back();
    }

    public function getusers()
    {
        $search = \request()->query('search');
        return User::where('email','like','%'.$search.'%')->pluck("email");
    }

    function showUploadForm(){
        return view('admin.user.upload');
    }

    function submitUploadForm(Request $request){
        if($request->hasFile('users')){
            $this->dispatch(new UploadUsersJob($request->users));
        }
        return redirect()->back();
    }
}