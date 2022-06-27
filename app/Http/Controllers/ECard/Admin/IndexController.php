<?php

namespace App\Http\Controllers\ECard\Admin;

use App\BusinessCardUser;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    function index()
    {
        $query = BusinessCardUser::query();

        if ($search = \request('search')) {
            $query->where('employee_id', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%');
        }

        $users = $query->orderBy('name')->paginate();
        return view('e-card.admin.user.index', compact('users'));
    }
}
