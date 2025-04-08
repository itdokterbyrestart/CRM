<?php

namespace App\Http\Controllers;

use App\Models\ProductGroup;
use Auth;

class ProductGroupController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage productgroups')) {
            return abort(403);
        }
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['name' => "Product groep"]
        ];

        return view('content.productgroup.index', compact('breadcrumbs'));
    }

    public function edit($productgroup_id)
    {
        $productgroup = ProductGroup::findOrFail($productgroup_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('productgroup.index'), 'name' => "Product groepen"], ['link' => route('productgroup.show', $productgroup->id), 'name' => $productgroup->name], ['name' => "Aanpassen"]
        ];

        return view('content.productgroup.form', compact('breadcrumbs','productgroup','edit'));
    }

    public function create()
    {
        $productgroup = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('productgroup.index'), 'name' => "Product groepen"], ['name' => "Nieuw"]
        ];

        return view('content.productgroup.form', compact('breadcrumbs','productgroup','edit'));
    }

    public function show($productgroup_id)
    {
        $productgroup = ProductGroup::findOrFail($productgroup_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('productgroup.index'), 'name' => "Product groepen"], ['name' => $productgroup->name]
        ];

        return view('content.productgroup.form', compact('breadcrumbs','productgroup','edit'));
    }
}
