<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\Datatables\Datatables;
use Illuminate\Validation\Rule;
use App\User;

class ManageUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage user');
    }

    public function index()
    {
        if (request()->ajax()) {

            $users = User::with('roles')
                    ->whereHas('roles', function($roles){ $roles->whereNotIn('name', ['user']); })
                    ->orDoesntHave('roles')
                    ->get();

            return DataTables::of($users)
                        ->addColumn('viewBtn', '<button type="button" class="view btn-primary">Manage User</button>')
                        ->rawColumns(['viewBtn'])
                        ->editColumn('created_at', function ($orders) {
                          return date('F, d Y, g:i a', strtotime($orders->created_at));
                        })
                        ->make(true); //return modified datatables
        }

        return view('admin._manageuser');
    }

    public function show(Request $request)
    {
        $user = User::with('roles')->where('id', $request->id)->first();
        $roles = Role::all()->pluck('name', 'id')->map(function($roles){ return ucwords($roles);});
        return compact('user', 'roles');
    }

    public function update(User $user, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)]
        ]);

        $user->update($validated);
        $user->syncRoles($request->role);

        return 'User has been updated!';
    }

    public function destroy(User $user)
    {
        $user->syncRoles([]);
        $user->delete();
    }
}
