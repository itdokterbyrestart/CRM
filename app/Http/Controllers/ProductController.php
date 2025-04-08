<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['name' => "Producten"]
        ];

        return view('content.product.index', compact('breadcrumbs'));
    }

    public function edit($product_id)
    {
        $product = Product::with('services')->findOrFail($product_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('product.index'), 'name' => "Producten"], ['link' => route('product.show', $product->id), 'name' => $product->name], ['name' => "Aanpassen"]
        ];

        return view('content.product.form', compact('breadcrumbs','product','edit'));
    }

    public function create()
    {
        $product = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('product.index'), 'name' => "Producten"], ['name' => "Nieuw"]
        ];

        return view('content.product.form', compact('breadcrumbs','product','edit'));
    }

    public function show($product_id)
    {
        $product = Product::with('services')->findOrFail($product_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('product.index'), 'name' => "Producten"], ['name' => $product->name]
        ];

        return view('content.product.form', compact('breadcrumbs','product','edit'));
    }
}
