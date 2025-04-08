<?php

namespace App\Http\Controllers;
use Auth;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage roles')) {
            return abort(403);
        }

        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['name' => "Rollen"]
        ];

        return view('content.role.index', compact('breadcrumbs'));
    }

    public function edit($role_id)
    {
        $role = Role::with('permissions')->findOrFail($role_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('role.index'), 'name' => "Rollen"], ['link' => route('role.show', $role->id), 'name' => $role->name], ['name' => "Aanpassen"]
        ];

        return view('content.role.form', compact('breadcrumbs','role','edit'));
    }

    public function create()
    {
        $role = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('role.index'), 'name' => "Rollen"], ['name' => "Nieuw"]
        ];

        return view('content.role.form', compact('breadcrumbs','role','edit'));
    }

    public function show($role_id)
    {
        $role = Role::with('permissions')->findOrFail($role_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('role.index'), 'name' => "Rollen"], ['name' => $role->name]
        ];

        return view('content.role.form', compact('breadcrumbs','role','edit'));
    }
}