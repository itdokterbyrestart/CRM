<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;

class UserController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage users')) {
            return abort(403);
        }
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['name' => "Gebruikers"]
        ];

        return view('content.user.index', compact('breadcrumbs'));
    }

    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('user.index'), 'name' => "Gebruikers"], ['link' => route('user.show', $user->id), 'name' => $user->name], ['name' => "Aanpassen"]
        ];

        return view('content.user.form', compact('breadcrumbs','user','edit'));
    }

    public function create()
    {
        $user = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('user.index'), 'name' => "Gebruikers"], ['name' => "Nieuw"]
        ];

        return view('content.user.form', compact('breadcrumbs','user','edit'));
    }

    public function show($user_id)
    {
        $user = user::findOrFail($user_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('user.index'), 'name' => "Gebruikers"], ['name' => $user->name]
        ];

        return view('content.user.form', compact('breadcrumbs','user','edit'));
    }
}
