<?php

namespace App\Http\Controllers;
use App\Models\{
    Invoice,
    Setting,
};

use Illuminate\Support\Facades\Cache;

use App\Mail\{
	InvoiceMail,
    InvoiceReminderMail,
};
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['name' => "Facturen"]
        ];

        return view('content.invoice.index', compact('breadcrumbs'));
    }

    public function show_frontend($id)
    {
        // Define page settings
        $pageConfigs = ['blankPage' => true];

        // Define email
        $business_email = Setting::where('name','business_email')->first()->value ?? 'info@deitdokter.nl';

        // Define phone
        $business_phone = Setting::where('name','business_phone')->first()->value ?? '0031 6 82041651';

        // Define iban
        $business_iban = Setting::where('name','business_iban')->first()->value ?? 'NL&nbsp;84&nbsp;INGB&nbsp;0675&nbsp;3897&nbsp;20';

        // Define bank
        $business_bank = Setting::where('name','business_bank')->first()->value ?? 'ING';

        // Define VAT number
        $business_VAT_number = Setting::where('name','business_VAT_number')->first()->value ?? 'NL004087316B24';

        // Define kvk
        $business_kvk = Setting::where('name','business_kvk')->first()->value ?? '85378526';

        // Define business_address
        $business_address = Setting::where('name','business_address')->first()->value ?? 'Stationsstraat&nbsp;32a<br>5461&nbsp;JV&nbsp;&nbsp;Veghel';

        // Define invoice_text
        $invoice_comments_text = Setting::where('name','invoice_comments_text')->first()->value ?? 'Ik verzoek u vriendelijk het verschuldigde bedrag binnen 14 dagen te betalen via de betaallink in deze factuur of door deze over te maken op bovenstaand rekeningnummer onder vermelding van het factuurnummer.';

        // Define email_template_color
        $email_template_color = Setting::where('name','email_template_color')->first()->value;

        $cache = Cache::get('invoice' . $id);

        if ($cache === null) {
            $invoice = Invoice::with(['order_products' => function ($q) { $q->orderBy('pivot_order'); }])->with(['order_hours' => function ($q) { $q->orderBy('date'); }])->with('order.customer')->findOrFail($id);

            Cache::forever('invoice' . $invoice->id,[
                'invoice' => $invoice
            ]);
        } else {
            $invoice = $cache['invoice'] ?? Invoice::with(['order_products' => function ($q) { $q->orderBy('pivot_order'); }])->with(['order_hours' => function ($q) { $q->orderBy('date'); }])->with('order.customer')->findOrFail($id);
        }

        $products = $invoice->order_products;
        $hours = $invoice->order_hours;
        $customer = $invoice->order->customer;

        // Define status
        $status = $invoice->invoice_statuses->first();
        
        if ($status->name == 'Nog maken' || $status->name == 'Controleren' || $status->name == 'Wachten op versturen' AND !auth::check()) {
            return abort(404, 'De factuur is niet beschikbaar');
        }

        // Define totals
        $total_price_customer_including_tax = number_format($invoice->total_price_customer_including_tax,2,',','');
        $total_tax_amount = number_format($invoice->total_tax_amount,2,',','');
        $total_price_customer_excluding_tax = number_format($invoice->total_price_customer_excluding_tax,2,',','');

        // Record page count, with cooldown of 60 minutes between requests if no signed in user
        if (!Auth::user()) {
            views($invoice)
                ->cooldown(60)
                ->record();
        }

        // Return view of invoice
        return view('frontend.invoice.show', compact('pageConfigs','invoice','customer','products','hours','total_price_customer_including_tax','total_tax_amount','total_price_customer_excluding_tax','status','business_phone','business_email','business_iban','business_kvk','business_VAT_number','business_bank','business_address','invoice_comments_text','email_template_color'));
    }

    public function email_preview($invoice_id)
	{
		return new InvoiceMail($invoice_id);
	}

    public function email_reminder_preview($invoice_id)
	{
		return new InvoiceReminderMail($invoice_id);
	}

    public function edit($invoice_id)
    {
        $invoice = Invoice::with('invoice_statuses')->findOrFail($invoice_id);
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('invoice.index'), 'name' => "Facturen"], ['link' => route('invoice.show', $invoice->id), 'name' => $invoice->invoice_number], ['name' => "Aanpassen"]
        ];

        return view('content.invoice.form', compact('breadcrumbs','invoice','edit'));
    }

    public function create()
    {
        $invoice = null;
        $edit = 1;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('invoice.index'), 'name' => "Facturen"], ['name' => "Nieuw"]
        ];

        return view('content.invoice.form', compact('breadcrumbs','invoice','edit'));
    }

    public function show($invoice_id)
    {
        $invoice = Invoice::with('invoice_statuses')->findOrFail($invoice_id);
        $edit = 0;
        
        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('invoice.index'), 'name' => "Offertes"], ['name' => $invoice->invoice_number]
        ];

        return view('content.invoice.form', compact('breadcrumbs','invoice','edit'));
    }
}
