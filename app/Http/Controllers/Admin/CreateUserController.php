<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\User;

class CreateUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:add user');
    }

    public function create()
    {
        $roles = Role::all()->pluck('name');
        return view('admin._usercreate', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => 'required'
        ]);

        User::firstOrCreate([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->assignRole($request->role);
        
        return redirect()->back()->with('success' , 'A new user has been successfully created.');
    }
}
