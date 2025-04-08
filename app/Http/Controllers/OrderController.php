<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['name' => "Opdrachten"]
        ];

        return view('content.order.index', compact('breadcrumbs'));
    }

    public function edit($order_id)
    {
        $order = Order::with('order_products.invoices','order_hours.invoices','invoices.invoice_statuses')->withSum('order_products', 'purchase_price_excluding_tax')->withSum('order_products', 'revenue')->withSum('order_hours', 'amount_revenue_excluding_tax')->findOrFail($order_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('order.index'), 'name' => "Opdrachten"], ['link' => route('order.show', $order->id), 'name' => $order->title], ['name' => "Aanpassen"]
        ];

        return view('content.order.form', compact('breadcrumbs','order','edit'));
    }

    public function create()
    {
        $order = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('order.index'), 'name' => "Opdrachten"], ['name' => "Nieuw"]
        ];

        return view('content.order.form', compact('breadcrumbs','order','edit'));
    }

    public function show($order_id)
    {
        $order = Order::with('order_products.invoices','order_hours.invoices','invoices.invoice_statuses')->withSum('order_products', 'purchase_price_excluding_tax')->withSum('order_products', 'revenue')->withSum('order_hours', 'amount_revenue_excluding_tax')->findOrFail($order_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('order.index'), 'name' => "Opdrachten"], ['name' => $order->title]
        ];

        return view('content.order.form', compact('breadcrumbs','order','edit'));
    }  
}
