<?php

namespace App\Http\Controllers;

use App\Models\{
    Quote,
    Setting,
};

use App\Mail\{
	QuoteMail,
};
use Illuminate\Support\Facades\Cache;
Use Carbon\Carbon;
use Auth;

class QuoteController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['name' => "Offertes"]
        ];

        return view('content.quote.index', compact('breadcrumbs'));
    }

    public function show_frontend($quote_id)
    {
        $pageConfigs = ['blankPage' => true];

        $cache = Cache::get('quote' . $quote_id);

        if ($cache === null) {
            $quote = Quote::with('product_groups.products','quote_statuses','quote_products')->findOrFail($quote_id);
            $quote_products = $quote->quote_products;
            $quote_product_groups = $quote->product_groups;

            Cache::forever('quote' . $quote->id,[
                'quote_products' => $quote_products,
                'quote_product_groups' => $quote_product_groups,
            ]);
        } else {
            $quote = Quote::findOrFail($quote_id);
            $quote_products = $cache['quote_products'] ?? $quote->quote_products;
            $quote_product_groups = $cache['quote_product_groups'] ?? $quote->product_groups;
        }
        
        if ($quote->quote_statuses->first()->name == 'Verlopen') {
            $expiration_date = Carbon::parse($quote->expiration_date ?? $quote->quote_statuses->first()->created_at);
            $amount_of_days_expired_invoice_viewable = (int)Setting::where('name','amount_of_days_expired_invoice_viewable')->first()->value ?? 45;
    
            if ($expiration_date->addDays($amount_of_days_expired_invoice_viewable) < Carbon::now()->startOfDay()) {
                abort(404, 'De offerte is verlopen');
            }
        };

        $quote_products = $quote_products->sortBy('order');

        if ($quote->quote_text == '' OR $quote->quote_text == null) {
            $quote->quote_text = Setting::where('name','quote_text')->first()->value ?? "Bedankt voor je interesse. Ik hoor graag of je akkoord gaat met de offerte.<br><br>Met vriendelijke groet,<br>['company']";
        }

        $placeholders = ["['first_name']", "['full_name']", "['email']", "['company']", "['quote_title']", "['party_date']", "['start_time']", "['end_time']", "['party_type']", "['location']", "['guest_amount']"];
        $values = [ucfirst(strtok($quote->customer->name, " ")), ucfirst($quote->customer->name), $quote->customer->email, config('app.name'), lcfirst($quote->title), Carbon::parse($quote->party_date ?? Carbon::now()->startOfDay())->translatedFormat('j F Y'), substr($quote->start_time, 0, 5) ?? '00:00', substr($quote->end_time, 0, 5) ?? '00:00', lcfirst($quote->party_type ?? 'feest'), ucfirst($quote->location ?? 'Onbekend'), $quote->guest_amount ?? 0];

        $quote->quote_text = str_replace($placeholders, $values, $quote->quote_text);

        $terms_and_services_link = Setting::where('name','terms_and_services_link')->first()->value;
        $deposit_enabled = Setting::where('name','enable_deposit')->first()->value ?? 0;
        $deposit_percentage_amount = Setting::where('name','deposit_percentage_amount')->first()->value ?? 0;

        $package_image = $quote->getMedia('packages')->count() > 0 ? $quote->getMedia('packages')->first()->getUrl() : null;

        // Record page count, with cooldown of 120 minutes between requests if no signed in user
        if (!Auth::user()) {
            views($quote)
                ->cooldown(120)
                ->record();
        }

        return view('frontend.quote.show', compact('pageConfigs','quote','quote_products','quote_product_groups','terms_and_services_link','package_image','deposit_enabled','deposit_percentage_amount'));
    }

    public function email_preview($quote_id)
	{
		return new QuoteMail($quote_id);
	}

    public function edit($quote_id)
    {
        $quote = Quote::with('quote_products', 'product_groups','quote_statuses','selected_quote_products')->findOrFail($quote_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('quote.index'), 'name' => "Offertes"], ['link' => route('quote.show', $quote->id), 'name' => $quote->title], ['name' => "Aanpassen"]
        ];

        return view('content.quote.form', compact('breadcrumbs','quote','edit'));
    }

    public function create()
    {
        $quote = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('quote.index'), 'name' => "Offertes"], ['name' => "Nieuw"]
        ];

        return view('content.quote.form', compact('breadcrumbs','quote','edit'));
    }

    public function show($quote_id)
    {
        $quote = Quote::with('quote_products', 'product_groups','quote_statuses','selected_quote_products')->findOrFail($quote_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('quote.index'), 'name' => "Offertes"], ['name' => $quote->title]
        ];

        return view('content.quote.form', compact('breadcrumbs','quote','edit'));
    }
}
