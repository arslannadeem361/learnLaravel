<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::orderBy('id','DESC')->get();
        return view('roles.index',compact('roles'));
    }

    public function create()
    {
        $permission = Permission::get();
        return view('roles.create',compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')->with('success','Role created successfully');
    }

    public function edit($id)
    {
        $role = Role::where('id', $id)->first();

        return view('roles.edit')->with(compact('role'));
    }

    public function update(Request $request)
    {
        $update_role = Role::where('id', $request->role_id)->update([
            'name' => $request->edit_role
        ]);
        $update_role->syncPermissions($request->input('permission'));

        if($update_role)
        {
            return redirect()->route('roles.index')->with('success','Role updated successfully');
        }else{
            return redirect()->route('roles.index')->with('danger','Error while updating record.');
        }
    }

    public function destroy(Request $request)
    {
        $roleId = $request->roleId;

        $deleteRole = Role::where('id', $roleId)->delete();

        if(!empty($deleteRole)){
            return "success";
        }else{
            return "error";
        }
    }
}
