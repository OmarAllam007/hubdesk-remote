<?php

namespace App\Http\Requests;

class AdminUserRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = $this->route()->parameter('user');

        $validatePassword = !empty($user) && !empty($this->get('password'));

        $rules = [
            'name' => 'required',
//            'email' => 'required_if:email,email',
            'employee_id'=>'unique:users,employee_id'. ($user ? ',' . $user->id : ''),
            'login' => 'unique:users,login' . ($user ? ',' . $user->id : ''),
        ];

        if ($validatePassword) {
            $rules['password'] = 'min:8|confirmed';
        }

        return $rules;
    }
}
