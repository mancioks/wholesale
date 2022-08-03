<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreUserSettingsRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('user.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        User::create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'password' => Hash::make($request->post('password')),
            'role_id' => $request->post('role_id'),
            'pvm' => $request->post('pvm') ? 1:0,
        ]);

        return redirect()->back()->with('status', 'User created');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $warehouses = Warehouse::all();

        return view('user.edit', compact('user', 'roles', 'warehouses'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if(auth()->user()->role->id !== Role::SUPER_ADMIN && $request->post('role_id') !== null) {
            return redirect()->back()->withErrors(['Unable to change role']);
        }

        $user->update([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'role_id' => $request->post('role_id') ?: $user->role->id,
            'pvm' => $request->post('pvm') ? 1:0,
            'warehouse_id' => $request->post('warehouse_id') !== 'null' ? $request->post('warehouse_id') : null,
        ]);

        return redirect()->back()->with('status', 'User updated');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('status', 'User deleted');
    }

    public function settings()
    {
        $user = auth()->user();
        return view('user.settings', compact('user'));
    }

    public function storeSettings(StoreUserSettingsRequest $request)
    {
        $user = auth()->user();

        if($user->details()->exists()) {
            $user->details()->update($request->validated() + ['get_email_notifications' => $request->post('get_email_notifications') ? 1:0,]);
        } else {
            UserDetails::query()->create($request->validated() + ['user_id' => $user->id, 'get_email_notifications' => $request->post('get_email_notifications') ? 1:0,]);
        }

        return redirect()->back()->with('status', 'ok');
    }

    public function activate(User $user)
    {
        $user->update(['activated' => 1]);
        return redirect()->back()->with('status', 'User activated');
    }

    public function deactivate(User $user)
    {
        $user->update(['activated' => 0]);
        return redirect()->back()->with('status', 'User deactivated');
    }
}
