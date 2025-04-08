<?php

namespace App\Http\Controllers;
use Auth;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage permissions')) {
            return abort(403);
        }
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['name' => "Permissies"]
        ];

        return view('content.permission.index', compact('breadcrumbs'));
    }

    public function edit($permission_id)
    {
        $permission = Permission::findOrFail($permission_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('permission.index'), 'name' => "Permissies"], ['link' => route('permission.show', $permission->id), 'name' => $permission->name], ['name' => "Aanpassen"]
        ];

        return view('content.permission.form', compact('breadcrumbs','permission','edit'));
    }

    public function create()
    {
        $permission = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('permission.index'), 'name' => "Permissies"], ['name' => "Nieuw"]
        ];

        return view('content.permission.form', compact('breadcrumbs','permission','edit'));
    }

    public function show($permission_id)
    {
        $permission = Permission::findOrFail($permission_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('permission.index'), 'name' => "Permissies"], ['name' => $permission->name]
        ];

        return view('content.permission.form', compact('breadcrumbs','permission','edit'));
    }
}
