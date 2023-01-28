<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users');
    }

    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }
}
