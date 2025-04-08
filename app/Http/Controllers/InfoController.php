<?php

namespace App\Http\Controllers;

class InfoController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['name' => "Informatie"]
        ];
        
        return view('content.info.index', compact('breadcrumbs'));
    }
}
