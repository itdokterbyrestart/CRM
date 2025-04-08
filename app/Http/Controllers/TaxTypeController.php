<?php

namespace App\Http\Controllers;

use App\Models\TaxType;
use Auth;

class TaxTypeController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage taxtypes')) {
            return abort(403);
        }
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['name' => "Belastingtypes"]
        ];

        return view('content.taxtype.index', compact('breadcrumbs'));
    }

    public function edit($taxtype_id)
    {
        $taxtype = TaxType::findOrFail($taxtype_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('taxtype.index'), 'name' => "Belastingtypes"], ['link' => route('taxtype.show', $taxtype->id), 'name' => $taxtype->name], ['name' => "Aanpassen"]
        ];

        return view('content.taxtype.form', compact('breadcrumbs','taxtype','edit'));
    }

    public function create()
    {
        $taxtype = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('taxtype.index'), 'name' => "Belastingtypes"], ['name' => "Nieuw"]
        ];

        return view('content.taxtype.form', compact('breadcrumbs','taxtype','edit'));
    }

    public function show($taxtype_id)
    {
        $taxtype = TaxType::findOrFail($taxtype_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => 'javascript:void(0)', 'name' => "Beheer"], ['link' => route('taxtype.index'), 'name' => "Product groepen"], ['name' => $taxtype->name]
        ];

        return view('content.taxtype.form', compact('breadcrumbs','taxtype','edit'));
    }
}
