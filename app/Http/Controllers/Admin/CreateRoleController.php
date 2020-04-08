<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:add role');
    }

    public function create()
    {
        $permissions = Permission::all()->pluck('name');
        return view('admin._rolecreate', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles'],
            'permission' => 'required'
        ]);

        Role::firstOrCreate(['name' => strtolower($request->name)])->syncPermissions($request->permission);

        return redirect()->back()->with('success' , 'A new role has been successfully created.');
    }
}
