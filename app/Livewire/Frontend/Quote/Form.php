<?php

namespace App\Livewire\Frontend\Quote;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Quote,
    QuoteStatus,
    Service,
    SelectedQuoteProduct,
    Setting,
};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteMailConfirmation;
use App\Mail\QuoteStatusConfirmation;

class Form extends Component
{
    public $quote, $quotestatuses, $quote_products = [], $quote_product_groups = [];
    public $selected_products = [], $selected_products_amount = [], $selected_products_total_cost_excluding_tax = [], $selected_products_total_cost_including_tax = [], $selected_products_product_groups = [], $selected_products_product_groups_amount = [], $selected_products_product_groups_total_cost_including_tax = [], $selected_products_product_groups_total_cost_excluding_tax = [], $selected_services = [];
    public $status = '', $comment;
    public $total_cost = 0;
    public $terms_and_services, $terms_and_services_link;
    public $street, $number, $place_name, $postal_code, $company, $company_boolean = 0, $invoice_address_boolean = 0;

    protected $listeners = [
        'getModel',
        'forcedCloseModal',
    ];

    protected function rules()
    {
        return [
            'status' => 'required|string|min:2',
            'comment' => 'required_if:status,weigeren|string|nullable|min:2',
            'selected_products' => 'array',
            'selected_products_product_groups' => 'array',
            'selected_services' => 'array',
            'selected_products_amount' => 'array',
            'selected_products_amount.*' => 'numeric|between:0.01,999',
            'selected_products_product_groups_amount' => 'array',
            'selected_products_product_groups_amount.*' => 'numeric|between:0.01,999',
            'selected_products_total_cost_excluding_tax' => 'array',
            'selected_products_total_cost_excluding_tax.*' => 'numeric|between:-99999999.99,99999999.99',
            'selected_products_total_cost_including_tax' => 'array',
            'selected_products_total_cost_including_tax.*' => 'numeric|between:-99999999.99,99999999.99',
            'selected_products_product_groups_total_cost_including_tax' => 'array',
            'selected_products_product_groups_total_cost_including_tax.*' => 'numeric|between:-99999999.99,99999999.99',
            'selected_products_product_groups_total_cost_excluding_tax' => 'array',
            'selected_products_product_groups_total_cost_excluding_tax.*' => 'numeric|between:-99999999.99,99999999.99',
            'terms_and_services' => 'accepted_if:status,accepteren',
            'number' => 'nullable|string|max:255|required_if:invoice_address_boolean,1',
            'street' => 'nullable|string|max:255|required_if:invoice_address_boolean,1',
            'postal_code' => 'nullable|string|max:255|required_if:invoice_address_boolean,1',
            'place_name' => 'nullable|string|max:255|required_if:invoice_address_boolean,1',
            'company' => 'nullable|string|max:255|required_if:company_boolean,1',
        ];
    }

    protected $validationAttributes = [
        'status' => 'status',
        'comment' => 'commentaar',
        'selected_products' => 'geselecteerde producten',
        'selected_products_product_groups' => 'geselecteerde producten',
        'selected_services' => 'geselecteerde services',
        'selected_products_amount' => 'aantal',
        'selected_products_product_groups_amount' => 'aantal',
        'selected_products_total_cost_excluding_tax' => 'totale kosten',
        'selected_products_total_cost_including_tax' => 'totale kosten',
        'selected_products_product_groups_total_cost_including_tax' => 'totale kosten',
        'selected_products_product_groups_total_cost_excluding_tax' => 'totale kosten',
        'terms_and_services' => 'algemene voorwaarden',
        'street' => 'straat',
        'number' => 'nummer',
        'postal_code' => 'postcode',
        'place_name' => 'plaatsnaam',
        'company' => 'bedrijf',
        'invoice_address_boolean' => 'factuuradres verplicht',
    ];

    public function mount()
    {
        $this->quotestatuses = QuoteStatus::whereIn('name',['Akkoord','Geweigerd'])->get();
        $this->terms_and_services_link = Setting::where('name','terms_and_services_link')->first()->value;
    }

    public function getModel($quote_id, $accepted)
    {
        $this->quote = Quote::findOrFail($quote_id);
        $this->status = ($accepted == 1 ? 'accepteren' : 'weigeren');
        $cache = Cache::get('quote' . $this->quote->id);
        $this->quote_products = $cache['quote_products'];
        $this->quote_product_groups = $cache['quote_product_groups'];
        $customer = $this->quote->customer;
        if ((empty($customer->street) OR empty($customer->number) OR empty($customer->postal_code) OR empty($customer->place_name)) AND ($this->status == 'accepteren')) {
            $this->street = $customer->street;
            $this->number = $customer->number;
            $this->place_name = $customer->place_name;
            $this->postal_code = $customer->postal_code;
            $this->company = $customer->company;
            if (!empty($this->company)) {
                $this->company_boolean = 1;
            }
            $this->invoice_address_boolean = 1;
        }
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'status') {
            dd('status gewijzigd');
        }
        $this->validateOnly($propertyName);
    }

    public function updatedSelectedProducts($value, $id)
    {
        if ($value === false) {
            unset($this->selected_products[$id], $this->selected_products_amount[$id], $this->selected_products_total_cost_excluding_tax[$id], $this->selected_products_total_cost_including_tax[$id]);
        } else {
            $product = $this->quote_products->find($id);
            $this->selected_products_amount[$id] = $product->amount;
            $this->selected_products_total_cost_excluding_tax[$id] = $product->price_customer_excluding_tax;
            $this->selected_products_total_cost_including_tax[$id] = $product->price_customer_including_tax;
            $this->CalculateProductTotalCost($id);
        }
        $this->CalculateTotalCost();
    }

    public function updatedSelectedProductsProductGroups($value, $id)
    {
        if ($value === false) {
            unset($this->selected_products_product_groups[$id], $this->selected_products_product_groups_amount[$id], $this->selected_products_product_groups_total_cost_including_tax[$id], $this->selected_products_product_groups_total_cost_excluding_tax[$id]);
        } else {
            $this->selected_products_product_groups_amount[$id] = 1;
            // Obtain selected product
            foreach ($this->quote_product_groups as $quote_product_group) {
                foreach ($quote_product_group->products as $product) {
                    if ($product->id == $id) {
                        $selected_product = $product;
                    }
                }
            }
            $this->selected_products_product_groups_total_cost_including_tax[$id] = $selected_product->price_customer_including_tax;
            $this->selected_products_product_groups_total_cost_excluding_tax[$id] = $selected_product->price_customer_excluding_tax;
        }
        $this->CalculateTotalCost();
    }

    public function updatedSelectedServices($value, $id)
    {
        if ($value === false) {
            unset($this->selected_services[$id]);
        }
        $this->CalculateTotalCost();
    }

    public function updatedSelectedProductsAmount($value, $id)
    {
        if (is_numeric($value)) {
            $this->CalculateProductTotalCost($id);
        }
        $this->CalculateTotalCost();
    }

    public function updatedSelectedProductsProductGroupsAmount($value, $id)
    {
        if (is_numeric($value)) {
            $this->CalculateProductGroupProductTotalCost($id);
        }
        $this->CalculateTotalCost();
    }

    private function CalculateProductTotalCost($id)
    {
        // With discount
        if ($this->quote_products->find($id)->use_discount_prices == 1) {
            $this->selected_products_total_cost_excluding_tax[$id] = number_format(($this->quote_products->find($id)->discount_price_customer_excluding_tax * $this->selected_products_amount[$id]), 2, '.','');
            $this->selected_products_total_cost_including_tax[$id] = number_format(($this->quote_products->find($id)->discount_price_customer_including_tax * $this->selected_products_amount[$id]), 2, '.','');
        } else {
            // Normal
            $this->selected_products_total_cost_excluding_tax[$id] = number_format(($this->quote_products->find($id)->price_customer_excluding_tax * $this->selected_products_amount[$id]), 2, '.','');
            $this->selected_products_total_cost_including_tax[$id] = number_format(($this->quote_products->find($id)->price_customer_including_tax * $this->selected_products_amount[$id]), 2, '.','');
        }
    }

    private function CalculateProductGroupProductTotalCost($id)
    {
        foreach ($this->quote_product_groups as $quote_product_group) {
            foreach ($quote_product_group->products as $product) {
                if ($product->id == $id) {
                    $selected_product = $product;
                }
            }
        }
        $this->selected_products_product_groups_total_cost_including_tax[$id] = number_format(($selected_product->price_customer_including_tax * $this->selected_products_product_groups_amount[$id]), 2, '.','');
        $this->selected_products_product_groups_total_cost_excluding_tax[$id] = number_format(($selected_product->price_customer_excluding_tax * $this->selected_products_product_groups_amount[$id]), 2, '.','');
    }

    private function CalculateTotalCost()
    {
        $this->total_cost = 0;
        // Selected products
        foreach ($this->selected_products_total_cost_including_tax as $product_total_cost) {
            $this->total_cost = $this->total_cost + $product_total_cost;
        }
        // Selected product group products
        foreach ($this->selected_products_product_groups_total_cost_including_tax as $product_total_cost) {
            $this->total_cost = $this->total_cost + $product_total_cost;
        }
        $this->total_cost = number_format($this->total_cost, 2, '.', '');
    }

    public function store()
    {
        $this->validate();

        if ($this->status == 'accepteren') {
            $status_submit = QuoteStatus::where('name', 'Akkoord')->first();
        } elseif ($this->status == 'weigeren') {
            $status_submit = QuoteStatus::where('name', 'Geweigerd')->first();
        } else {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De opgegeven status is ongeldig.',
                'text' => 'Probeer het opnieuw, ververs eventueel de pagina.',
            ]);
        }
        $clientIP = \Request::ip() ?? 'Onbekend';

        try {
            DB::transaction(function () use ($status_submit, $clientIP) {
                $business_email = Setting::where('name','business_email')->first()->value ?? 'info@deitdokter.nl';

                $this->quote->quote_statuses()->attach($status_submit,[
                    'comment' => $this->comment,
                ]);

                foreach ($this->selected_products as $key => $value) {
                    $product = $this->quote_products->find($key);

                    $price_customer_excluding_tax = ($product->use_discount_prices == 1 ? $product->discount_price_customer_excluding_tax : $product->price_customer_excluding_tax);
                    $price_customer_including_tax = ($product->use_discount_prices == 1 ? $product->discount_price_customer_including_tax : $product->price_customer_including_tax);

                    SelectedQuoteProduct::create([
                        'name' => $product->name,
                        'description' => $product->description,
                        'purchase_price_excluding_tax' => $product->purchase_price_excluding_tax,
                        'purchase_price_including_tax' => $product->purchase_price_including_tax,
                        'price_customer_excluding_tax' => $price_customer_excluding_tax,
                        'price_customer_including_tax' => $price_customer_including_tax,
                        'amount' => $this->selected_products_amount[$key] ?? 1,
                        'total_price_customer_excluding_tax' => $this->selected_products_total_cost_excluding_tax[$key] ?? 0,
                        'total_price_customer_including_tax' => $this->selected_products_total_cost_including_tax[$key] ?? 0,
                        'tax_percentage' => $product->tax_percentage,
                        'quote_id' => $this->quote->id,
                    ]);
                }

                foreach ($this->selected_products_product_groups as $key => $value) {

                    // Obtain chosen product
                    foreach ($this->quote_product_groups as $quote_product_group) {
                        foreach ($quote_product_group->products as $product) {
                            if ($product->id == $key) {
                                $selected_product = $product;
                            }
                        }
                    }

                    SelectedQuoteProduct::create([
                        'name' => $selected_product->name,
                        'description' => $selected_product->description,
                        'purchase_price_excluding_tax' => $selected_product->purchase_price_excluding_tax,
                        'purchase_price_including_tax' => $selected_product->purchase_price_including_tax,
                        'price_customer_excluding_tax' => $selected_product->price_customer_excluding_tax,
                        'price_customer_including_tax' => $selected_product->price_customer_including_tax,
                        'amount' => $this->selected_products_product_groups_amount[$key] ?? 1,
                        'total_price_customer_excluding_tax' => $this->selected_products_product_groups_total_cost_excluding_tax[$key] ?? 0,
                        'total_price_customer_including_tax' => $this->selected_products_product_groups_total_cost_including_tax[$key] ?? 0,
                        'tax_percentage' => $selected_product->tax_percentage,
                        'quote_id' => $this->quote->id,
                    ]);
                }

                if (count($this->selected_services) > 0) {
                    $service_names = '';
                    $i = 0;
                    $len = count($this->selected_services);
                    foreach ($this->selected_services as $key => $value) {
                        $service = Service::with('product')->find($key);
                        $service_names = $service_names . $service->name . ($i != $len - 1 ? ', ' : '');
                        $i++;
                        SelectedQuoteProduct::create([
                            'name' => $service->product->name,
                            'description' => $service->product->description,
                            'purchase_price_excluding_tax' => $service->product->purchase_price_excluding_tax,
                            'purchase_price_including_tax' => $service->product->purchase_price_including_tax,
                            'price_customer_excluding_tax' => $service->product->price_customer_excluding_tax,
                            'price_customer_including_tax' => $service->product->price_customer_including_tax,
                            'amount' => 1,
                            'total_price_customer_excluding_tax' => $service->product->price_customer_excluding_tax,
                            'total_price_customer_including_tax' => $service->product->price_customer_including_tax,
                            'tax_percentage' => $service->product->tax_percentage,
                            'quote_id' => $this->quote->id,
                        ]);
                    }
                    $this->quote->update([
                        'description' => ($this->quote->description . ' - Gekozen services: ' . $service_names),
                    ]);
                }

                if ($this->invoice_address_boolean == 1) {
                    $this->quote->customer->update([
                        'street' => $this->street,
                        'number' => $this->number,
                        'postal_code' => $this->postal_code,
                        'place_name' => $this->place_name,
                        'company' => $this->company,
                    ]);
                }

                Mail::to($this->quote->customer->email)
                    ->send((new QuoteMailConfirmation($this->quote->id)));

                Mail::to($business_email)
                    ->send((new QuoteStatusConfirmation($this->quote->id, $clientIP)));
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Er is een fout opgetreden!',
                'text' => 'Probeer het opnieuw of neem contact op.',
            ]);
        }

        $this->dispatch('closeModal');
        $this->dispatch('refreshParent')->to('frontend.quote.show-status');

        if ($status_submit->name == 'Akkoord') {
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'Bedankt voor je akkoord!',
                'text' => 'Je ontvangt binnen enkele minuten een bevestiging in je email.',
            ]);
        } else {
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'Wat jammer dat je de offerte geweigerd hebt.',
                'text' => 'Je ontvangt binnen enkele minuten een bevestiging in je email. Mocht het nodig zijn neem ik contact met je op.',
            ]);
        }

        $this->cleanVars();
    }

    private function cleanVars()
    {
        $this->quote = null;
        $this->status = '';
        $this->comment = null;
        $this->quote_products = [];
        $this->quote_product_groups = [];
        $this->selected_products = [];
        $this->selected_products_amount = [];
        $this->selected_products_total_cost_excluding_tax = [];
        $this->selected_products_total_cost_including_tax = [];
        $this->selected_products_product_groups = [];
        $this->selected_products_product_groups_amount = [];
        $this->selected_products_product_groups_total_cost_including_tax = [];
        $this->selected_products_product_groups_total_cost_excluding_tax = [];
        $this->selected_services = [];
        $this->total_cost = 0;
        $this->terms_and_services = null;
    }

    public function forcedCloseModal()
    {
        $this->cleanVars();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('frontend.quote.livewire.form');
    }
}
