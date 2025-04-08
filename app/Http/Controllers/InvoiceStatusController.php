<?php

namespace App\Http\Controllers;

use App\Models\InvoiceStatus;
use Auth;

class InvoiceStatusController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage invoicestatuses')) {
            return abort(403);
        }
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['name' => "Factuur Status"]
        ];

        return view('content.invoicestatus.index', compact('breadcrumbs'));
    }

    public function edit($invoicestatus_id)
    {
        $invoicestatus = InvoiceStatus::findOrFail($invoicestatus_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('invoicestatus.index'), 'name' => "Factuur status"], ['link' => route('invoicestatus.show', $invoicestatus->id), 'name' => $invoicestatus->name], ['name' => "Aanpassen"]
        ];

        return view('content.invoicestatus.form', compact('breadcrumbs','invoicestatus','edit'));
    }

    public function create()
    {
        $invoicestatus = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('invoicestatus.index'), 'name' => "Factuur status"], ['name' => "Nieuw"]
        ];

        return view('content.invoicestatus.form', compact('breadcrumbs','invoicestatus','edit'));
    }

    public function show($invoicestatus_id)
    {
        $invoicestatus = InvoiceStatus::findOrFail($invoicestatus_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('invoicestatus.index'), 'name' => "Factuur status"], ['name' => $invoicestatus->name]
        ];

        return view('content.invoicestatus.form', compact('breadcrumbs','invoicestatus','edit'));
    }
}
