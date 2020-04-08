<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\Datatables\Datatables;

class ManageRoleController extends Controller
{
	public function __construct()
    {
        $this->middleware('can:manage role');
    }

    public function index(){

    	if (request()->ajax()) {

            $roles = Role::whereNotIn('name', ['user'])->with('permissions')->get();

            return DataTables::of($roles)
                        ->addColumn('viewBtn', '<button type="button" class="view btn-primary">Manage Role</button>')
                        ->rawColumns(['viewBtn'])
                        ->make(true); //return modified datatables
        }

    	return view('admin._managerole');
    }

    public function show(Role $role)
    {
        //eager loading the permissions
        $role->permissions;
        $permissions = Permission::all()->pluck('name', 'id')->map(function($roles){ return ucwords($roles);});
        return compact('role', 'permissions');
    }

    public function update(Request $request, Role $role){

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $role->update(['name' => $request->name]);

        $role->syncPermissions($request->permissions);

        // return $validated; 
        return 'Role has been updated!';
    }

    public function destroy(Role $role){
        $role->syncPermissions([]);
        $role->delete();
    }
}
