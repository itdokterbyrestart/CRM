<?php

namespace App\Http\Controllers;

use App\Models\OrderStatus;
use Auth;

class OrderStatusController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage orderstatuses')) {
            return abort(403);
        }
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['name' => "Opdracht Status"]
        ];

        return view('content.orderstatus.index', compact('breadcrumbs'));
    }

    public function edit($orderstatus_id)
    {
        $orderstatus = OrderStatus::findOrFail($orderstatus_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('orderstatus.index'), 'name' => "Opdracht status"], ['link' => route('orderstatus.show', $orderstatus->id), 'name' => $orderstatus->name], ['name' => "Aanpassen"]
        ];

        return view('content.orderstatus.form', compact('breadcrumbs','orderstatus','edit'));
    }

    public function create()
    {
        $orderstatus = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('orderstatus.index'), 'name' => "Opdracht status"], ['name' => "Nieuw"]
        ];

        return view('content.orderstatus.form', compact('breadcrumbs','orderstatus','edit'));
    }

    public function show($orderstatus_id)
    {
        $orderstatus = OrderStatus::findOrFail($orderstatus_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('orderstatus.index'), 'name' => "Opdracht status"], ['name' => $orderstatus->name]
        ];

        return view('content.orderstatus.form', compact('breadcrumbs','orderstatus','edit'));
    }
}
