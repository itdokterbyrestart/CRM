<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Invoice,
    InvoiceStatus,
    Order,
    OrderStatus,
};

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class Form extends Component
{
    // General
    public $edit = 1;
    // Invoice
    public $invoice_number, $invoice_date, $expiration_date, $status = '', $status_comment, $custom_text, $extra_invoice_data, $order_id, $updated_at, $created_at, $modelId, $order_products = [], $order_products_order = [], $order_hours = [], $selected_order_products = [], $selected_order_hours = [], $selected_order_hours_comment = [], $order_title, $order_customer, $missing_invoice_numbers = [], $show_missing_invoice_numbers = false, $selected_order_products_comment = [];
    public $invoicestatus;
    // Previous status
    public $previous_invoice_status;
    public $previous_invoice_statuses = [];
    // Total cost, revenue and profit of order
    public $total_price_customer_excluding_tax = 0, $total_tax_amount = 0, $total_price_customer_including_tax = 0, $selected_order_products_revenue = [], $selected_order_products_total_tax_amount = [], $selected_order_products_tax_percentage = [], $selected_order_products_revenue_including_tax = [];
    public $hour_amount_price_excluding_tax = [], $hour_tax_percentage = [], $hour_amount_price_including_tax = [];
    public $calculation_method_excluding_tax = false, $flush_cache = false;

    public function mount($model, $edit)
    {
        $this->invoicestatus = InvoiceStatus::orderBy('order')->get();
        $this->invoice_number = (Invoice::where('invoice_number','like',Carbon::now()->year .'%')->max('invoice_number') ?? (Carbon::now()->year * 1000)) + 1;
        $this->status = $this->invoicestatus->where('name', 'Nog maken')->first()->id ?? '';
        $this->invoice_date = Carbon::now()->format('Y-m-d');
        $this->expiration_date = Carbon::now()->addDays(14)->format('Y-m-d');
        if (Route::currentRouteName() == 'invoice.create') {
            if(isset($_GET['order_id'])) {
                $this->order_id = $_GET['order_id'];
                $this->createInvoice($this->order_id);
            }
        }
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit === 0) {
                $this->created_at = Carbon::parse($model->created_at)->format('Y-m-d');
                $this->updated_at = Carbon::parse($model->updated_at)->format('Y-m-d');
            }
            $this->invoice_number = $model->invoice_number;
            $this->invoice_date = Carbon::parse($model->invoice_date)->format('Y-m-d');
            $this->expiration_date = Carbon::parse($model->expiration_date)->format('Y-m-d');
            $this->status = $model->invoice_statuses->first()->id;
            $this->status_comment = $model->invoice_statuses->first()->pivot->comment;
            $this->order_id = $model->order_id;
            $this->total_price_customer_excluding_tax = $model->total_price_customer_excluding_tax;
            $this->total_tax_amount = $model->total_tax_amount;
            $this->total_price_customer_including_tax = $model->total_price_customer_including_tax;
            $this->custom_text = $model->custom_text;
            $this->extra_invoice_data = $model->extra_invoice_data;
            $this->calculation_method_excluding_tax = ($model->calculation_method_excluding_tax == 1 ? true : false);
            $model2 = Order::with('order_products','order_hours')->find($this->order_id);
            $this->order_products = $model2->order_products;
            $this->order_title = $model2->title;
            $this->order_customer = $model2->customer->name;
            foreach ($model->order_products as $order_product) {
                $this->selected_order_products[$order_product->id] = true;
                $this->order_products_order[$order_product->id] = $order_product->pivot->order ?? (is_array($this->order_products_order) && !empty($this->order_products_order) ? max($this->order_products_order) + 1 : 1);
                $this->selected_order_products_revenue[$order_product->id] = $order_product->revenue;
                $this->selected_order_products_tax_percentage[$order_product->id] = $order_product->tax_percentage;
                $this->selected_order_products_total_tax_amount[$order_product->id] = $order_product->total_price_customer_including_tax - $order_product->revenue;
                $this->selected_order_products_revenue_including_tax[$order_product->id] = $order_product->total_price_customer_including_tax;
                $this->selected_order_products_comment[$order_product->id] = $order_product->pivot->comment ?? '';
            }
            $this->order_hours = $model2->order_hours;
            foreach ($model->order_hours as $order_hour) {
                $this->selected_order_hours[$order_hour->id] = true;
                $this->selected_order_hours_comment[$order_hour->id] = $order_hour->pivot->comment ?? '';
                $this->hour_amount_price_excluding_tax[$order_hour->id] = $order_hour->amount_revenue_excluding_tax;
                $this->hour_tax_percentage[$order_hour->id] = $order_hour->tax_percentage;
                $this->hour_amount_price_including_tax[$order_hour->id] = $order_hour->amount_revenue_including_tax;
            }
            foreach ($model2->order_products as $order_product) {
                if (!isset($this->order_products_order[$order_product->id])) {
                    $this->order_products_order[$order_product->id] = is_array($this->order_products_order) && !empty($this->order_products_order) ? max($this->order_products_order) + 1 : 1;
                }
            }
            if ($edit === 0) {
                $this->previous_invoice_statuses = $model->invoice_statuses->toArray();
            }
        }
    }

    protected function rules()
    {
        return [
            'invoice_number' => 'required|integer|digits:7|unique:invoices,invoice_number,' . $this->modelId,
            'invoice_date' => 'required|date',
            'expiration_date' => 'required|date|after:invoice_date',
            'status' => 'required|integer|exists:invoice_statuses,id',
            'status_comment' => 'sometimes|nullable|string',
            'order_id' => 'required|integer|exists:orders,id',
            'custom_text' => 'sometimes|nullable|string',
            'extra_invoice_data' => 'sometimes|nullable|string',
            'selected_order_products' => 'array',
            'selected_order_hours' => 'array',
            'selected_order_hours_comment' => 'array',
            'selected_order_hours_comment.*' => 'string|max:255',
            'selected_order_products_comment' => 'array',
            'selected_order_products_comment.*' => 'string|max:255',
            'calculation_method_excluding_tax' => 'required|boolean',
        ];
    }

    protected $validationAttributes = [
        'invoice_number' => 'factuurnummer',
        'invoice_date' => 'factuurdatum',
        'expiration_date' => 'vervaldatum',
        'status' => 'status',
        'status_comment' => 'status commentaar',
        'order_id' => 'opdracht id',
        'custom_text' => 'aangepaste tekst',
        'extra_invoice_data' => 'extra factuur data',
        'selected_order_products' => 'geselecteerde producten',
        'selected_order_hours' => 'geselecteerde uren',
        'selected_order_hours_comment' => 'uurcommentaar',
        'selected_order_hours_comment.*' => 'uurcommentaar',
        'selected_order_products_comment' => 'productcommentaar',
        'selected_order_products_comment.*' => 'productcommentaar',
        'calculation_method_excluding_tax' => 'rekenmethode zonder BTW',
    ];

    #[On('createInvoice')]
    public function createInvoice($order_id)
    {
        $this->invoice_number = (Invoice::where('invoice_number','like',Carbon::now()->year .'%')->max('invoice_number') ?? (Carbon::now()->year * 1000)) + 1;
        $this->status = InvoiceStatus::first()->id ?? '';
        $this->invoice_date = Carbon::now()->format('Y-m-d');
        $this->expiration_date = Carbon::now()->addDays(14)->format('Y-m-d');
        $this->order_id = $order_id;
        $model = Order::with('order_products','order_hours')->find($this->order_id);
        $this->order_title = $model->title;
        $this->order_customer = $model->customer->name;
        $this->order_products = $model->order_products;
        $this->order_hours = $model->order_hours;
        foreach ($model->order_products as $order_product) {
            $this->selected_order_products[$order_product->id] = ($order_product->price_customer_excluding_tax > 0 ? true : false);
            $this->order_products_order[$order_product->id] = $order_product->order ?? (is_array($this->order_products_order) && !empty($this->order_products_order) ? max($this->order_products_order) + 1 : 1);
            $this->selected_order_products_revenue[$order_product->id] = $order_product->revenue;
            $this->selected_order_products_tax_percentage[$order_product->id] = $order_product->tax_percentage;
            $this->selected_order_products_total_tax_amount[$order_product->id] = $order_product->total_price_customer_including_tax - $order_product->revenue;
            $this->selected_order_products_revenue_including_tax[$order_product->id] = $order_product->total_price_customer_including_tax;
            if ($order_product->price_customer_excluding_tax > 0) {
                foreach (preg_split("/\n|\r\n/", $order_product->description) as $description) {
                    if (str_contains($description, 'Looptijd')) {
                        $this->selected_order_products_comment[$order_product->id] = $description;
                        break;
                    } else {
                        $this->selected_order_products_comment[$order_product->id] = '';
                    }
                }
                if ($order_product->name == 'APK aan huis' || $order_product->name == 'APK op afstand') {
                    $this->custom_text = 'Afgelopen maand heb ik bij jou de APK uitgevoerd, hierbij ben ik niks geks tegengekomen.';
                }
            }
        }
        foreach ($model->order_hours as $order_hour) {
            $this->selected_order_hours[$order_hour->id] = ($order_hour->price_customer_excluding_tax > 0 ? true : false);
            if ($order_hour->price_customer_excluding_tax > 0) {
                $this->selected_order_hours_comment[$order_hour->id] = '';
            }
            $this->hour_amount_price_excluding_tax[$order_hour->id] = $order_hour->amount_revenue_excluding_tax;
            $this->hour_tax_percentage[$order_hour->id] = $order_hour->tax_percentage;
            $this->hour_amount_price_including_tax[$order_hour->id] = $order_hour->amount_revenue_including_tax;
        }
        $this->calculateInvoiceTotals();
    }

    public function reorder_product($index, $direction) {
        if ($direction == 'up' && ($this->order_products_order[$index] > min($this->order_products_order))) {
            $index_old_order = array_search($this->order_products_order[$index] - 1, $this->order_products_order);
            $this->order_products_order[$index] = $this->order_products_order[$index] - 1;
            $this->order_products_order[$index_old_order] = $this->order_products_order[$index_old_order] + 1;
        }
        
        if ($direction == 'down' && ($this->order_products_order[$index] < max($this->order_products_order))) {
            $index_old_order = array_search($this->order_products_order[$index] + 1, $this->order_products_order);
            $this->order_products_order[$index] = $this->order_products_order[$index] + 1;
            $this->order_products_order[$index_old_order] = $this->order_products_order[$index_old_order] - 1;
        }
        $this->flush_cache = true;
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedStatus()
    {
        $this->status_comment = null;
        $this->flush_cache = true;
    }

    public function updatedSelectedOrderHours($value, $id)
    {
        if ($value == false) {
            unset($this->selected_order_hours[$id], $this->selected_order_hours_comment[$id], $this->hour_amount_price_excluding_tax[$id], $this->hour_tax_percentage[$id], $this->hour_amount_price_including_tax[$id]);
        } else {
            $hour = $this->order_hours->find($id);
            $this->selected_order_hours_comment[$id] = '';
            $this->hour_amount_price_excluding_tax[$id] = $hour->amount_revenue_excluding_tax;
            $this->hour_tax_percentage[$id] = $hour->tax_percentage;
            $this->hour_amount_price_including_tax[$id] = $hour->amount_revenue_including_tax;
        }
        $this->calculateInvoiceTotals();
        $this->flush_cache = true;
    }

    public function updatedSelectedOrderProducts($value, $id)
    {
        if ($value == false) {
            unset($this->selected_order_products[$id], $this->selected_order_products_revenue[$id], $this->selected_order_products_tax_percentage[$id], $this->selected_order_products_revenue_including_tax[$id], $this->selected_order_products_total_tax_amount[$id], $this->selected_order_products_comment[$id]);
        } else {
            $order_product = $this->order_products->find($id);
            $this->selected_order_products_revenue[$id] = $order_product->revenue;
            $this->selected_order_products_tax_percentage[$id] = $order_product->tax_percentage;
            $this->selected_order_products_total_tax_amount[$id] = $order_product->total_price_customer_including_tax - $order_product->revenue;
            $this->selected_order_products_revenue_including_tax[$id] = $order_product->total_price_customer_including_tax;
            foreach (preg_split("/\n|\r\n/", $order_product->description) as $description) {
                if (str_contains($description, 'Looptijd')) {
                    $this->selected_order_products_comment[$order_product->id] = $description;
                    break;
                }
            }
            $this->selected_order_products_comment[$id] = '';
        }
        $this->calculateInvoiceTotals();
        $this->flush_cache = true;
    }

    public function updatedOrderId($value)
    {
        $this->cleanVars();
        $this->createInvoice($value);
        $this->flush_cache = true;
    }

    public function updatedInvoiceDate()
    {
        $this->expiration_date = Carbon::parse($this->invoice_date)->addDays(14)->format('Y-m-d');
        $this->flush_cache = true;
    }

    public function updatedCalculationMethodExcludingTax()
    {
        $this->calculateInvoiceTotals();
        $this->flush_cache = true;
    }

    public function updatedExtraInvoiceData()
    {
        $this->flush_cache = true;
    }

    public function updatedCustomText()
    {
        $this->flush_cache = true;
    }

    public function updatedStatusComment()
    {
        $this->flush_cache = true;
    }

    public function updatedExpirationDate()
    {
        $this->flush_cache = true;
    }

    public function updatedInvoiceNumber()
    {
        $this->flush_cache = true;
    }

    public function updatedSelectedOrderProductsComment()
    {
        $this->flush_cache = true;
    }

    public function updatedSelectedOrderHoursComment()
    {
        $this->flush_cache = true;
    }

    private function calculateInvoiceTotals()
    {
        $array_tax_and_price_customer_excluding_tax = [];
        $this->total_price_customer_excluding_tax = 0;
        $this->total_price_customer_including_tax = 0;
        $this->total_tax_amount = 0;

        foreach($this->selected_order_products as $id => $value) {
            $product_revenue = isset($this->selected_order_products_revenue[$id]) ? $this->selected_order_products_revenue[$id] : null;
            $product_tax_percentage = isset($this->selected_order_products_tax_percentage[$id]) ? $this->selected_order_products_tax_percentage[$id] : null;
            $product_revenue_including_tax = isset($this->selected_order_products_revenue_including_tax[$id]) ? $this->selected_order_products_revenue_including_tax[$id] : null;

            if (
                (is_float($product_revenue) OR is_numeric($product_revenue)) AND 
                (is_float($product_tax_percentage) OR is_numeric($product_tax_percentage)) AND
                (is_float($product_revenue_including_tax) OR is_numeric($product_revenue_including_tax)))
            {
                $array_tax_and_price_customer_excluding_tax[] = [
                    'tax_percentage' => $product_tax_percentage,
                    'price_customer_excluding_tax' => $product_revenue,
                    'price_customer_including_tax' => $product_revenue_including_tax,
                ];
            }
        }
    
        foreach($this->selected_order_hours as $id => $value) {
            $hour_price_customer_excluding_tax = isset($this->hour_amount_price_excluding_tax[$id]) ? $this->hour_amount_price_excluding_tax[$id] : null;
            $hour_tax_percentage = isset($this->hour_tax_percentage[$id]) ? $this->hour_tax_percentage[$id] : null;
            $hour_price_customer_including_tax = isset($this->hour_amount_price_including_tax[$id]) ? $this->hour_amount_price_including_tax[$id] : null;

            if (
                (is_float($hour_price_customer_excluding_tax) OR is_numeric($hour_price_customer_excluding_tax)) AND 
                (is_float($hour_price_customer_including_tax) OR is_numeric($hour_price_customer_including_tax)) AND
                (is_float($hour_tax_percentage) OR is_numeric($hour_tax_percentage)))
            {
                $array_tax_and_price_customer_excluding_tax[] = [
                    'tax_percentage' => $hour_tax_percentage,
                    'price_customer_excluding_tax' => $hour_price_customer_excluding_tax,
                    'price_customer_including_tax' => $hour_price_customer_including_tax,
                ];
            }
        }

        $price_customer_per_tax_percentage = [];
        $price_customer_per_tax_percentage = array_reduce($array_tax_and_price_customer_excluding_tax,function($carry,$item){
            if(!isset($carry[$item['tax_percentage']])){
                $carry[$item['tax_percentage']] = $item[($this->calculation_method_excluding_tax == false ? 'price_customer_including_tax' : 'price_customer_excluding_tax')];
            } else {
                $carry[$item['tax_percentage']] += $item[($this->calculation_method_excluding_tax == false ? 'price_customer_including_tax' : 'price_customer_excluding_tax')];
            }
            return $carry;
        }) ?? [];

        if ($price_customer_per_tax_percentage === null) {
            $price_customer_per_tax_percentage = [];
        }

        if ($this->calculation_method_excluding_tax == false) {
            foreach ($price_customer_per_tax_percentage as $tax_percentage => $price_customer)
            {
                $price_customer_excluding_tax = round($price_customer / ($tax_percentage / 100 + 1), 2);
                $price_customer_including_tax = round($price_customer, 2);
                $tax_amount = round(($price_customer_including_tax - $price_customer_excluding_tax), 2);
    
                $this->total_price_customer_excluding_tax = $this->total_price_customer_excluding_tax + $price_customer_excluding_tax;
                $this->total_price_customer_including_tax = $this->total_price_customer_including_tax + $price_customer_including_tax;
                $this->total_tax_amount = $this->total_tax_amount + $tax_amount;
            }
        } else {
            foreach ($price_customer_per_tax_percentage as $tax_percentage => $price_customer)
            {
                $price_customer_excluding_tax = round($price_customer, 2);
                $price_customer_including_tax = round((($tax_percentage / 100 + 1) * $price_customer), 2);
                $tax_amount = round(($price_customer_including_tax - $price_customer_excluding_tax), 2);
    
                $this->total_price_customer_excluding_tax = $this->total_price_customer_excluding_tax + $price_customer_excluding_tax;
                $this->total_price_customer_including_tax = $this->total_price_customer_including_tax + $price_customer_including_tax;
                $this->total_tax_amount = $this->total_tax_amount + $tax_amount;
            }
        }

        $this->total_price_customer_excluding_tax = number_format($this->total_price_customer_excluding_tax, 2, '.', '');
        $this->total_price_customer_including_tax = number_format($this->total_price_customer_including_tax, 2, '.', '');
        $this->total_tax_amount = number_format($this->total_tax_amount, 2, '.', '');
    }
    
    public function store()
    {
        $this->validate();

        try{
            DB::transaction(function () { 

                $this->calculateInvoiceTotals();

                $invoice = Invoice::with('invoice_statuses','order')->updateOrCreate(
                    ['id' => $this->modelId],
                    [
                        'invoice_number' => $this->invoice_number,
                        'invoice_date' => $this->invoice_date,
                        'expiration_date' => $this->expiration_date,
                        'order_id' => $this->order_id,
                        'custom_text' => trim($this->custom_text, " \n\r\t\v\0"),
                        'extra_invoice_data' => trim($this->extra_invoice_data, " \n\r\t\v\0"),
                        'total_price_customer_excluding_tax' => $this->total_price_customer_excluding_tax,
                        'total_tax_amount' => $this->total_tax_amount,
                        'total_price_customer_including_tax' => $this->total_price_customer_including_tax,
                        'calculation_method_excluding_tax' => $this->calculation_method_excluding_tax,
                    ]
                );

                $this->modelId = $invoice->id;
                
                $selected_order_products_array = [];
                $order_selected_product = 1;
                asort($this->order_products_order);
                foreach ($this->order_products_order as $order_product_id => $order) {
                    if (count($this->order_products->where('id',$order_product_id)) > 0 AND isset($this->selected_order_products[$order_product_id]) AND isset($this->selected_order_products[$order_product_id]) ? $this->selected_order_products[$order_product_id] == true : '') {
                        $selected_order_products_array[$order_product_id] = [
                            'comment' => $this->selected_order_products_comment[$order_product_id] != '' ? $this->selected_order_products_comment[$order_product_id] : null,
                            'order' => $order_selected_product++,
                        ];
                    }
                }

                $invoice->order_products()->sync($selected_order_products_array);

                $selected_order_hours_array = [];
                foreach($this->selected_order_hours as $id => $value) {
                    if (count($this->order_hours->where('id',$id)) > 0 AND $value === true) {
                        $selected_order_hours_array[$id] = [
                            'comment' => $this->selected_order_hours_comment[$id],
                        ];
                    }
                }

                $invoice->order_hours()->sync($selected_order_hours_array);

                $orderstatuses = OrderStatus::orderBy('order')->get();

                if (!in_array($invoice->order->order_status_id, $orderstatuses->whereIn('name',['Betaald, archief','Verloren','Gratis'])->pluck('id')->toArray())) {
                    if($this->status == $this->invoicestatus->where('name','Wachten op betaling')->first()->id) {
                        $invoice->order->update([
                            'order_status_id' => $orderstatuses->where('name','Wachten op betaling')->first()->id,
                        ]);
                    }

                    if(in_array($this->status, $this->invoicestatus->whereNotIn('name',['Wachten op betaling','Verlopen','Betaald','Geweigerd'])->pluck('id')->toArray())) {
                        $invoice->order->update([
                            'order_status_id' => $orderstatuses->where('name','Factureren')->first()->id,
                        ]);
                    }
                }

                
                $this->previous_invoice_status = $invoice->invoice_statuses->first();

                if ($this->status != (isset($this->previous_invoice_status) ? $this->previous_invoice_status->id : 0) OR $this->status_comment != (isset($this->previous_invoice_status) ? $this->previous_invoice_status->pivot->comment : '')) {
                    $invoice->invoice_statuses()->attach($this->status, [
                        'comment' => $this->status_comment,
                    ]);
                }

                if ($this->flush_cache === true) {
                    Cache::flush('invoice' . $this->modelId);
                }
 
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De factuur is niet aangepast',
                'text' => $e->getMessage(),
            ]);
        }
        
        return redirect(route('invoice.index'));
    }

    private function cleanVars()
    {
        $this->modelId = null;
        $this->updated_at = null;
        $this->created_at = null;
        $this->invoice_number = null;
        $this->invoice_date = null;
        $this->expiration_date = null;
        $this->status = '';
        $this->status_comment = null;
        $this->order_id = null;
        $this->edit = 1;
        $this->custom_text = null;
        $this->extra_invoice_data = null;
        $this->previous_invoice_status = null;
        $this->previous_invoice_statuses = [];
        $this->order_hours = [];
        $this->order_products = [];
        $this->selected_order_hours = [];
        $this->selected_order_products = [];
        $this->selected_order_hours_comment = [];
        $this->selected_order_products_comment = [];
        $this->order_title = null;
        $this->order_customer = null;
        $this->missing_invoice_numbers = [];
        $this->show_missing_invoice_numbers = false;
        $this->hour_amount_price_excluding_tax = [];
        $this->hour_tax_percentage = [];
        $this->hour_amount_price_including_tax = [];
        $this->selected_order_products_revenue = [];
        $this->selected_order_products_tax_percentage = [];
        $this->selected_order_products_total_tax_amount = [];
        $this->selected_order_products_revenue_including_tax = [];
        $this->total_price_customer_excluding_tax = 0;
        $this->total_tax_amount = 0;
        $this->total_price_customer_including_tax = 0;
        $this->order_products_order = [];
        $this->calculation_method_excluding_tax = false;
        $this->flush_cache = false;
    }

    public function render()
    {
        return view('content.invoice.livewire.form');
    }
}
