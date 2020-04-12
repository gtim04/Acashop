<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\Datatables\Datatables;
use Illuminate\Validation\Rule;
use App\User;

class ManageAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage admin');
    }

    public function index()
    {
        if (request()->ajax()) {

            $users = User::with('roles')
                    ->whereHas('roles', function($roles){ $roles->whereNotIn('name', ['user']);})
                    ->get();

            return DataTables::of($users)
                        ->addColumn('viewBtn', '<button type="button" class="view btn-primary">Manage Administrator</button>')
                        ->rawColumns(['viewBtn'])
                        ->make(true); //return modified datatables
        }

        return view('admin._manageadmin');
    }

    public function show(Request $request)
    {
        $user = User::with('roles')->where('id', $request->id)->first();
        $roles = Role::all()->pluck('name', 'id')->map(function($roles){ return ucwords($roles);});
        return compact('user', 'roles');
    }

    public function update(User $admin, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)]
        ]);

        $admin->update($validated);
        $admin->syncRoles($request->role);

        return 'User has been updated!';
    }

    public function destroy(User $admin)
    {
        $admin->syncRoles([]);
        $admin->delete();
    }
}
