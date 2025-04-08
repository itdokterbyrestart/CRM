<?php

namespace App\Http\Controllers;

use App\Models\QuoteStatus;
use Auth;

class QuoteStatusController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage quotestatuses')) {
            return abort(403);
        }
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['name' => "Offerte Status"]
        ];

        return view('content.quotestatus.index', compact('breadcrumbs'));
    }

    public function edit($quotestatus_id)
    {
        $quotestatus = QuoteStatus::findOrFail($quotestatus_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('quotestatus.index'), 'name' => "Offerte status"], ['link' => route('quotestatus.show', $quotestatus->id), 'name' => $quotestatus->name], ['name' => "Aanpassen"]
        ];

        return view('content.quotestatus.form', compact('breadcrumbs','quotestatus','edit'));
    }

    public function create()
    {
        $quotestatus = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('quotestatus.index'), 'name' => "Offerte status"], ['name' => "Nieuw"]
        ];

        return view('content.quotestatus.form', compact('breadcrumbs','quotestatus','edit'));
    }

    public function show($quotestatus_id)
    {
        $quotestatus = QuoteStatus::findOrFail($quotestatus_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('quotestatus.index'), 'name' => "Offerte status"], ['name' => $quotestatus->name]
        ];

        return view('content.quotestatus.form', compact('breadcrumbs','quotestatus','edit'));
    }
}
