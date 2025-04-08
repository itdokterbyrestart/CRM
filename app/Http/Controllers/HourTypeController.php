<?php

namespace App\Http\Controllers;

use App\Models\HourType;
use Auth;

class HourTypeController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage hourtypes')) {
            return abort(403);
        }
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['name' => "Uurtypes"]
        ];

        return view('content.hourtype.index', compact('breadcrumbs'));
    }

    public function edit($hourtype_id)
    {
        $hourtype = HourType::findOrFail($hourtype_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('hourtype.index'), 'name' => "Uurtypes"], ['link' => route('hourtype.show', $hourtype->id), 'name' => $hourtype->name], ['name' => "Aanpassen"]
        ];

        return view('content.hourtype.form', compact('breadcrumbs','hourtype','edit'));
    }

    public function create()
    {
        $hourtype = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('hourtype.index'), 'name' => "Uurtypes"], ['name' => "Nieuw"]
        ];

        return view('content.hourtype.form', compact('breadcrumbs','hourtype','edit'));
    }

    public function show($hourtype_id)
    {
        $hourtype = HourType::findOrFail($hourtype_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('hourtype.index'), 'name' => "Uurtypes"], ['name' => $hourtype->name]
        ];

        return view('content.hourtype.form', compact('breadcrumbs','hourtype','edit'));
    }
}
