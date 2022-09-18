<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreUserSettingsRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\OrderItem;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    public function orders(User $user)
    {
        return view('user.orders', compact('user'));
    }

    public function items(User $user, Request $request)
    {
        $orders = $user->orders()->whereNotIn('status_id', [Status::CANCELED, Status::DECLINED]);
        $filtering = false;

        $filterFrom = $request->get('filter_from');
        $filterTo = $request->get('filter_to');

        if($filterFrom || $filterTo) {
            if(!$filterFrom) $filterFrom = '2000-01-01';
            if(!$filterTo) $filterTo = date('Y-m-d');

            if(check_date_valid($filterFrom, 'Y-m-d') && check_date_valid($filterTo, 'Y-m-d')) {
                $filtering = true;

                $orders = $orders->whereBetween('created_at', [
                    Carbon::createFromFormat('Y-m-d', $filterFrom)->startOfDay()->toDateTimeString(),
                    Carbon::createFromFormat('Y-m-d', $filterTo)->endOfDay()->toDateTimeString()
                ]);
            }
        }

        $ordersId = $orders->pluck('id')->toArray();

        $items = OrderItem::query()->whereIn('order_id', $ordersId)->get();
        $items = $items->groupBy(['name', 'price']);

        return view('user.items', compact('items', 'user', 'filtering'));
    }
}
