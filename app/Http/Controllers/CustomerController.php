<?php

namespace App\Http\Controllers;

use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['name' => "Klanten"]
        ];

        return view('content.customer.index', compact('breadcrumbs'));
    }

    public function edit($customer_id)
    {
        $customer = Customer::with(['orders' => function ($q) {
                        $q->with('order_products','order_hours');
                    }])->findOrFail($customer_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('customer.index'), 'name' => "Klanten"], ['link' => route('customer.show', $customer->id), 'name' => $customer->name], ['name' => "Aanpassen"]
        ];

        return view('content.customer.form', compact('breadcrumbs','customer','edit'));
    }

    public function create()
    {
        $customer = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('customer.index'), 'name' => "Klanten"], ['name' => "Nieuw"]
        ];

        return view('content.customer.form', compact('breadcrumbs','customer','edit'));
    }

    public function show($customer_id)
    {
        $customer = Customer::with(['orders' => function ($q) {
                        $q->with('order_products','order_hours');
                    }])->findOrFail($customer_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('customer.index'), 'name' => "Klanten"], ['name' => $customer->name]
        ];

        return view('content.customer.form', compact('breadcrumbs','customer','edit'));
    }    
}
