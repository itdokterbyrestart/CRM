<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['name' => "Services"]
        ];

        return view('content.service.index', compact('breadcrumbs'));
    }

    public function users()
    {
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('service.index'),'name' => "Services"], ['name' => "APK"]
        ];

        return view('content.service.users.index', compact('breadcrumbs'));
    }

    public function edit($service_id)
    {
        $service = Service::findOrFail($service_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('service.index'), 'name' => "Services"], ['link' => route('service.show', $service->id), 'name' => $service->name], ['name' => "Aanpassen"]
        ];

        return view('content.service.form', compact('breadcrumbs','service','edit'));
    }

    public function create()
    {
        $service = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('service.index'), 'name' => "Services"], ['name' => "Nieuw"]
        ];

        return view('content.service.form', compact('breadcrumbs','service','edit'));
    }

    public function show($service_id)
    {
        $service = Service::findOrFail($service_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('service.index'), 'name' => "Services"], ['name' => $service->name]
        ];

        return view('content.service.form', compact('breadcrumbs','service','edit'));
    }
}
