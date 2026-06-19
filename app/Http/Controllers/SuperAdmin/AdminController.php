<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index(){
        $admins = Admin::all();
        return view('superadmin.admins.index', compact('admins'));
    }

    public function create(){
        return view('superadmin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable',
            'temple_name' => 'nullable',
            'mobile_number' => 'nullable|unique:admins,mobile_number',
            'email' => 'nullable|unique:admins,email',
            'password' => 'nullable|min:6',
            'profile_image' => 'nullable|image|mimes:png,svg,jpeg,jpg,gif,webp|max:5048',
        ]);

        $imagePath = null;
        if($request->hasFile('profile_image')){
            $imagePath = $request->file('profile_image')->store('profile_image', 'public');
        }

        Admin::create([
            'name' => $request->name,
            'temple_name' => $request->temple_name,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_image' => $imagePath,
        ]);
        return redirect()->route('superadmin.admins.index')->with('sucess', 'Created');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('superadmin.admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => 'nullable',
            'temple_name' => 'nullable',
            'mobile_number' => 'nullable|unique:admins,mobile_number,' . $admin->id,
            'email' => 'nullable|unique:admins,email,' . $admin->id,
            'password' => 'nullable|min:6',
            'profile_image' => 'nullable|image|mimes:png,svg,jpeg,jpg,gif,webp|max:5048',
        ]);

        if($request->hasFile('profile_image')){
            if($admin->profile_image){
                Storage::disk('public')->delete($admin->profile_image);
            }
            $admin->profile_image = $request->file('profile_image')->store('profile_image', 'public');
        }
        $admin->name = $request->name;
        $admin->temple_name = $request->temple_name;
        $admin->mobile_number = $request->mobile_number;
        $admin->email = $request->email;

        if($request->filled('password')){
            $admin->password = Hash::make($request->password);
        }
        $admin->save();
        return redirect()->route('superadmin.admins.index')->with('Success', 'Updated');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        if($admin->profile_image){
            Storage::disk('public')->delete($admin->profile_image);
        }
        $admin->delete();
        return redirect()->route('superadmin.admins.index')->with('success', 'Deleted');
    }
}
