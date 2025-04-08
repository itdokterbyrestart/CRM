<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Auth;

class SettingController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage settings')) {
            return abort(403);
        }
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['name' => "Instellingen"]
        ];

        return view('content.setting.index', compact('breadcrumbs'));
    }

    public function edit($setting_id)
    {
        $setting = Setting::findOrFail($setting_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('setting.index'), 'name' => "Instellingen"], ['link' => route('setting.show', $setting->id), 'name' => $setting->name], ['name' => "Aanpassen"]
        ];

        return view('content.setting.form', compact('breadcrumbs','setting','edit'));
    }

    public function create()
    {
        $setting = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('setting.index'), 'name' => "Instellingen"], ['name' => "Nieuw"]
        ];

        return view('content.setting.form', compact('breadcrumbs','setting','edit'));
    }

    public function show($setting_id)
    {
        $setting = Setting::findOrFail($setting_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('setting.index'), 'name' => "Instellingen"], ['name' => $setting->name]
        ];

        return view('content.setting.form', compact('breadcrumbs','setting','edit'));
    }
}
