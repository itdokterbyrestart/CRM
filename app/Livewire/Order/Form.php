<?php

namespace App\Livewire\Order;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Order,
    Customer,
    OrderStatus,
    Product,
    OrderProduct,
    User,
    HourType,
    Invoice,
    OrderHour,
    TaxType,
};

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    // General
    public $edit = 1;
    public $users, $tax_types;
    // Order
    public $title, $customer, $status = '', $description, $updated_at, $created_at, $total_cost, $total_revenue, $modelId;
    public $customers, $orderstatus;
    public $collapsed_view = false;
    // Order Product
    public $products, $orderproducts = [];
    public $product_id, $product_extra_option, $product_name, $product_supplier, $product_order_number, $product_user, $product_purchase_price_excluding_tax, $product_purchase_price_including_tax, $product_price_customer_excluding_tax, $product_price_customer_including_tax, $product_amount, $product_revenue, $product_profit, $product_tax_percentage, $product_description, $product_description_count, $product_total_price_customer_including_tax, $total_purchase_price_excluding_tax_order_product;
    public $product_updated_at, $product_created_at, $product_edit;
    public $order_products_to_be_removed = [];
    // Order hours
    public $hour_types, $orderhours = [];
    public $hour_id, $hour_name, $hour_price_customer_excluding_tax, $hour_price_customer_including_tax, $hour_date, $hour_start_time, $hour_end_time, $hour_amount, $hour_amount_price_excluding_tax, $hour_amount_price_including_tax, $hour_tax_percentage, $hour_description, $hour_user, $hour_kilometers, $hour_time_minutes;
    public $hour_updated_at, $hour_created_at, $hour_edit;
    public $order_hours_to_be_removed = [];
    // Invoices
    public $invoices = [];
    // Total cost, revenue and profit of order
    public $total_price_customer_excluding_tax = 0, $total_tax_amount = 0, $total_price_customer_including_tax = 0, $total_purchase_price_excluding_tax = 0, $total_profit = 0;

    public function mount($model, $edit)
    {
        $this->customers = Customer::orderBy('name')->get();
        $this->orderstatus = OrderStatus::orderBy('order')->get();
        $this->products = Product::orderBy('name')->get();
        $this->users = User::orderBy('name')->where('blocked', 0)->get();
        $this->hour_types = HourType::orderBy('name')->get();
        $this->tax_types = TaxType::orderBy('default','DESC')->orderBy('percentage','DESC')->get();
        $this->status = $this->orderstatus->where('name', 'Nog doen')->first()->id ?? '';
        $this->edit = $edit;
        
        if ($model) {
            $this->modelId = $model->id;
            $this->title = $model->title;
            $this->description = $model->description;
            $this->status = $model->order_status->id;
            $this->created_at = (date("Y-m-d",strtotime($model->created_at)));
            if ($edit === 0) {
                $this->updated_at = (date("Y-m-d",strtotime($model->updated_at)));
                if (Auth::user()->can('show costs')) {
                    $this->total_cost = $model->order_products_sum_purchase_price_excluding_tax ?? (number_format(0,2,'.',''));
                }
                if (Auth::user()->can('show revenue')) {
                    $this->total_revenue = number_format(($model->order_products_sum_revenue ?? 0) + ($model->order_hours_sum_amount_revenue_excluding_tax ?? 0),2, '.', '');
                }
            }
            $this->total_price_customer_excluding_tax = $model->total_price_customer_excluding_tax;
            $this->total_tax_amount = $model->total_tax_amount;
            $this->total_price_customer_including_tax = $model->total_price_customer_including_tax;
            $this->total_purchase_price_excluding_tax = $model->total_purchase_price_excluding_tax;
            $this->total_profit = $model->total_profit;
            $this->customer = $model->customer->id;
            
            foreach ($model->order_products as $index => $order_product) {
                $this->orderproducts[] = $order_product->order ?? (is_array($this->orderproducts) && !empty($this->orderproducts) ? max($this->orderproducts) + 1 : 1);
                $this->product_id[$index] = $order_product->id;
                if (count($this->products->where('name', $order_product->name)) === 0) {
                    $this->product_extra_option[$index] = $order_product->name;
                } else {
                    $this->product_extra_option[$index] = '';
                }
                $this->product_name[$index] = $order_product->name;
                $this->product_supplier[$index] = $order_product->supplier;
                $this->product_order_number[$index] = $order_product->order_number;
                $this->product_user[$index] = $order_product->user_id;
                $this->product_purchase_price_excluding_tax[$index] = $order_product->purchase_price_excluding_tax;
                $this->product_purchase_price_including_tax[$index] = $order_product->purchase_price_including_tax;
                $this->product_price_customer_excluding_tax[$index] = $order_product->price_customer_excluding_tax;
                $this->product_price_customer_including_tax[$index] = $order_product->price_customer_including_tax;
                $this->product_amount[$index] = $order_product->amount;
                $this->product_revenue[$index] = $order_product->revenue;
                $this->product_profit[$index] = $order_product->profit;
                $this->product_total_price_customer_including_tax[$index] = $order_product->total_price_customer_including_tax;
                $this->product_tax_percentage[$index] = $order_product->tax_percentage;
                $this->product_description[$index] = $order_product->description;
                $this->product_description_count[$index] = count(preg_split("/\n|\r\n/", $this->product_description[$index])) > 3 ? count(preg_split("/\n|\r\n/", $this->product_description[$index])) : 3;
                $this->product_created_at[$index] = $order_product->created_at->format('Y-m-d');
                $this->product_updated_at[$index] = $order_product->updated_at->format('Y-m-d');
                $this->product_edit[$index] = ($order_product->invoices->count() > 0 ? 0 : 1);
            }
            $this->orderproducts = array_values($this->orderproducts);
            foreach ($model->order_hours as $index => $order_hour) {
                $this->orderhours[] = '';
                $this->hour_id[$index] = $order_hour->id;
                $this->hour_name[$index] = $order_hour->name;
                $this->hour_price_customer_excluding_tax[$index] = $order_hour->price_customer_excluding_tax;
                $this->hour_price_customer_including_tax[$index] = $order_hour->price_customer_including_tax;
                $this->hour_date[$index] = (date("Y-m-d",strtotime($order_hour->date)));
                $this->hour_start_time[$index] = $order_hour->start_time;
                $this->hour_end_time[$index] = $order_hour->end_time;
                $this->hour_amount[$index] = $order_hour->amount;
                $this->hour_amount_price_excluding_tax[$index] = $order_hour->amount_revenue_excluding_tax;
                $this->hour_amount_price_including_tax[$index] = $order_hour->amount_revenue_including_tax;
                $this->hour_tax_percentage[$index] = $order_hour->tax_percentage;
                $this->hour_description[$index] = $order_hour->description;
                $this->hour_user[$index] = $order_hour->user_id;
                $this->hour_kilometers[$index] = $order_hour->kilometers;
                $this->hour_time_minutes[$index] = $order_hour->time_minutes;
                $this->hour_edit[$index] = ($order_hour->invoices->count() > 0 ? 0 : 1);
                if ($edit === 0) {
                    $this->hour_updated_at[$index] = $order_hour->updated_at->format('d-m-Y | H:i');
                    $this->hour_created_at[$index] = $order_hour->created_at->format('d-m-Y | H:i');
                }
            }
            $this->invoices = $model->invoices;
        }
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|min:2|max:255',
            'description' => 'sometimes|nullable|string',
            'customer' => 'required|integer|exists:customers,id',
            'status' => 'required|integer|exists:order_statuses,id',
            'created_at' => 'sometimes|nullable|date|after:1970-01-01|before:2038-01-19',

            'product_name.*' => 'required|string|max:255',
            'product_supplier.*' => 'sometimes|nullable|string|max:255',
            'product_order_number.*' => 'sometimes|nullable|string|max:255',
            'product_user.*' => 'sometimes|nullable|integer|exists:users,id',
            'product_purchase_price_excluding_tax.*' => 'required|numeric|between:-99999.99,99999.99',
            'product_purchase_price_including_tax.*' => 'required|numeric|between:-99999.99,99999.99',
            'product_price_customer_excluding_tax.*' => 'required|numeric|between:-99999.99,99999.99',
            'product_price_customer_including_tax.*' => 'required|numeric|between:-99999.99,99999.99',
            'product_total_price_customer_including_tax.*' => 'required|numeric|between:-99999999.99,99999999.99',
            'product_amount.*' => 'required|numeric|between:-9999.99,9999.99',
            'product_revenue.*' => 'required|numeric|between:-99999999.99,99999999.99',
            'product_profit.*' => 'required|numeric|between:-99999999.99,99999999.99',
            'product_tax_percentage.*' => 'required|numeric|between:0,100',
            'product_description.*' => 'sometimes|nullable|string',
            'product_created_at.*' => 'required|date|after:1970-01-01|before:2038-01-19',

            'hour_name.*' => 'required|string|max:255',
            'hour_user.*' => 'required|integer|exists:users,id',
            'hour_price_customer_excluding_tax.*' => 'required|numeric|between:-99999.99,99999.99',
            'hour_price_customer_including_tax.*' => 'required|numeric|between:-99999.99,99999.99',
            'hour_date.*' => 'required|date|before_or_equal:today',
            'hour_start_time.*' => 'required|string|regex:/[0-9]+:[0-9]+/i',
            'hour_end_time.*' => 'required|string|regex:/[0-9]+:[0-9]+/i',
            'hour_amount.*' => 'required|numeric|between:0,99.99',
            'hour_amount_price_excluding_tax.*' => 'required|numeric|between:0,99999999.99',
            'hour_amount_price_including_tax.*' => 'required|numeric|between:0,99999999.99',
            'hour_tax_percentage.*' => 'required|numeric|between:0,100',
            'hour_description.*' => 'required|string',
            'hour_kilometers.*' => 'sometimes|nullable|integer|max:2147483647',
            'hour_time_minutes.*' => 'sometimes|nullable|integer|max:2147483647',
        ];
    }

    protected $validationAttributes = [
        'title' => 'titel',
        'description' => 'beschrijving',
        'customer' => 'klant',
        'status' => 'status',
        'created_at' => 'gemaakt op',

        'product_name.*' => 'product naam',
        'product_supplier.*' => 'leverancier',
        'product_order_number.*' => 'bestelnummer',
        'product_user.*'=> 'gebruiker',
        'product_purchase_price_excluding_tax.*' => 'inkoopprijs excl. BTW',
        'product_purchase_price_including_tax.*' => 'inkoopprijs incl. BTW',
        'product_price_customer_excluding_tax.*' => 'klantprijs excl. BTW',
        'product_price_customer_including_tax.*' => 'klantprijs incl. BTW',
        'product_total_price_customer_including_tax.*' => 'klantprijs totaal incl. BTW',
        'product_amount.*' => 'aantal',
        'product_revenue.*' => 'opbrengst',
        'product_profit.*' => 'winst',
        'product_tax_percentage' => 'belasting percentage',
        'product_description.*' => 'product beschrijving',
        'product_created_at.*' => 'gemaakt op',

        'hour_name.*' => 'uurtype',
        'hour_user.*' => 'gebruiker',
        'hour_price_customer_excluding_tax.*' => 'uurprijs excl. BTW',
        'hour_price_customer_including_tax.*' => 'uurprijs incl. BTW',
        'hour_date.*' => 'datum',
        'hour_start_time.*' => 'starttijd',
        'hour_end_time.*' => 'eindtijd',
        'hour_amount.*' => 'aantal uren',
        'hour_amount_price_excluding_tax.*' => 'kosten klant excl. BTW',
        'hour_amount_price_including_tax.*' => 'kosten klant incl. BTW',
        'hour_tax_percentage' => 'belasting percentage',
        'hour_description.*' => 'beschrijving',
        'hour_kilometers.*' => 'kilometers',
        'hour_time_minutes.*' => 'reistijd',
    ];

    public function removeProduct($index)
    {
        if (isset($this->product_id[$index])) {
            $this->order_products_to_be_removed[] = $this->product_id[$index];
        }

        asort($this->orderproducts);

        unset($this->orderproducts[$index], $this->product_id[$index], $this->product_extra_option[$index], $this->product_name[$index], $this->product_supplier[$index], $this->product_order_number[$index], $this->product_user[$index], $this->product_purchase_price_excluding_tax[$index], $this->product_purchase_price_including_tax[$index],$this->product_price_customer_excluding_tax[$index], $this->product_price_customer_including_tax[$index], $this->product_total_price_customer_including_tax[$index], $this->product_amount[$index], $this->product_revenue[$index], $this->product_profit[$index], $this->product_tax_percentage[$index], $this->product_description[$index],$this->product_description_count[$index],$this->product_created_at[$index],$this->product_edit[$index]);

        $order = 1;
        foreach ($this->orderproducts as $index => $product_order) { 
            $this->orderproducts[$index] = $order++;
        }
    }

    public function addProduct()
    {
        $this->orderproducts[] = (is_array($this->orderproducts) && !empty($this->orderproducts) ? max($this->orderproducts) + 1 : 1);
        $this->product_extra_option[array_key_last($this->orderproducts)] = '';
        $this->product_name[array_key_last($this->orderproducts)] = '';
        $this->product_description_count[array_key_last($this->orderproducts)] = 3;
        $this->product_tax_percentage[array_key_last($this->orderproducts)] = $this->tax_types->where('default',1)->first()->percentage ?? 21;
        $this->product_created_at[] = Carbon::now()->format('Y-m-d');
        $this->product_edit[] = 1;
        $this->product_supplier[array_key_last($this->orderproducts)] = null;
        $this->product_order_number[array_key_last($this->orderproducts)] = null;
        $this->product_user[array_key_last($this->orderproducts)] = null;
        $this->product_purchase_price_excluding_tax[array_key_last($this->orderproducts)] = 0.00;
        $this->product_purchase_price_including_tax[array_key_last($this->orderproducts)] = 0.00;
        $this->product_price_customer_excluding_tax[array_key_last($this->orderproducts)] = 0.00;
        $this->product_price_customer_including_tax[array_key_last($this->orderproducts)] = 0.00;
        $this->product_amount[array_key_last($this->orderproducts)] = 1;
        $this->product_revenue[array_key_last($this->orderproducts)] = 0.00;
        $this->product_profit[array_key_last($this->orderproducts)] = 0.00;
        $this->product_total_price_customer_including_tax[array_key_last($this->orderproducts)] = 0.00;
        $this->product_description[array_key_last($this->orderproducts)] = null;
        $this->product_updated_at[array_key_last($this->orderproducts)] = null;
        $this->product_order_number[array_key_last($this->orderproducts)] = null;
        $this->product_order_number[array_key_last($this->orderproducts)] = null;
    }

    #[On('productSelected')]
    public function productSelected($id, $index)
    {
        $product = $this->products->find($id);

        if ($product) {
            $this->product_name[$index] = $product->name;
            $this->product_purchase_price_excluding_tax[$index] = number_format($product->purchase_price_excluding_tax, 2, '.', '');
            $this->product_purchase_price_including_tax[$index] = number_format($product->purchase_price_including_tax, 2, '.', '');
            $this->product_price_customer_excluding_tax[$index] = number_format($product->price_customer_excluding_tax, 2, '.', '');
            $this->product_price_customer_including_tax[$index] = number_format($product->price_customer_including_tax, 2, '.', '');
            $this->product_description[$index] = $product->description;
            $this->product_description_count[$index] = count(preg_split("/\n|\r\n/", $this->product_description[$index])) > 3 ? count(preg_split("/\n|\r\n/", $this->product_description[$index])) : 3;
            $this->product_tax_percentage[$index] = $product->tax_percentage;
        }

        if (empty($this->product_amount[$index])) {
            $this->product_amount[$index] = number_format(1, 2, '.', '');
        }
        $this->CalculateProductRevenue($index);
    }

    public function updatedProductUser($value, $index)
    {
        if ($value == ''){
            $this->product_user[$index] = null;
        }
    }

    public function updatedProductPurchasePriceExcludingTax($value, $index)
    {
        if (is_numeric($this->product_purchase_price_excluding_tax[$index])) {
            $this->product_purchase_price_excluding_tax[$index] = number_format($value, 2, '.', '');
        }
        $this->calculatePurchasePriceIncludingTax($index);
        $this->CalculateProductRevenue($index);
    }

    public function updatedProductPurchasePriceIncludingTax($value, $index)
    {
        if (is_numeric($this->product_purchase_price_including_tax[$index])) {
            $this->product_purchase_price_including_tax[$index] = number_format($value, 2, '.', '');
        }
        $this->calculatePurchasePriceExcludingTax($index);
        $this->CalculateProductRevenue($index);
    }

    public function updatedProductPriceCustomerExcludingTax($value, $index)
    {
        if (is_numeric($this->product_price_customer_excluding_tax[$index])) {
            $this->product_price_customer_excluding_tax[$index] = number_format($value, 2, '.', '');
        }
        $this->CalculateProductPriceCustomerIncludingTax($index);
        $this->CalculateProductRevenue($index);
    }

    public function updatedProductPriceCustomerIncludingTax($value, $index)
    {
        if (is_numeric($this->product_price_customer_including_tax[$index])) {
            $this->product_price_customer_including_tax[$index] = number_format($value, 2, '.', '');
        }
        $this->CalculateProductPriceCustomerExcludingTax($index);
        $this->CalculateProductRevenue($index);
    }

    public function updatedProductAmount($value, $index)
    {
        if (is_numeric($this->product_amount[$index])) {
            $this->product_amount[$index] = number_format($value, 2, '.', '');
        }
        $this->CalculateProductRevenue($index);
    }

    public function updatedProductTaxPercentage($value, $index)
    {
        $this->CalculateProductPriceCustomerIncludingTax($index);
        $this->calculatePurchasePriceIncludingTax($index);
        $this->CalculateProductRevenue($index);
    }

    private function calculatePurchasePriceIncludingTax($index)
    {
        if (!isset($this->product_tax_percentage[$index]) OR !isset($this->product_purchase_price_excluding_tax[$index])) {
            return;
        }
        
        if(is_numeric($this->product_purchase_price_excluding_tax[$index]) AND is_numeric($this->product_tax_percentage[$index])) {
            $this->product_purchase_price_including_tax[$index] = number_format(($this->product_purchase_price_excluding_tax[$index] * (1 + ($this->product_tax_percentage[$index] / 100))), 2, '.', '');
        }
    }

    private function calculatePurchasePriceExcludingTax($index)
    {
        if (!isset($this->product_tax_percentage[$index]) OR !isset($this->product_purchase_price_including_tax[$index])) {
            return;
        }
        
        if(is_numeric($this->product_purchase_price_including_tax[$index]) AND is_numeric($this->product_tax_percentage[$index])) {
            $this->product_purchase_price_excluding_tax[$index] = number_format(($this->product_purchase_price_including_tax[$index] / (1 + ($this->product_tax_percentage[$index] / 100))), 2, '.', '');
        }
    }

    private function CalculateProductPriceCustomerIncludingTax($index)
    {
        if (!isset($this->product_tax_percentage[$index]) OR !isset($this->product_price_customer_excluding_tax[$index])) {
            return;
        }

        if ((is_float($this->product_tax_percentage[$index]) OR is_numeric($this->product_tax_percentage[$index])) AND (is_float($this->product_price_customer_excluding_tax[$index]) OR is_numeric($this->product_price_customer_excluding_tax[$index]))) {
            $this->product_price_customer_including_tax[$index] = number_format(($this->product_price_customer_excluding_tax[$index] * (1 + ($this->product_tax_percentage[$index] / 100))), 2, '.', '');
        }
    }

    private function CalculateProductPriceCustomerExcludingTax($index)
    {
        if (!isset($this->product_tax_percentage[$index]) OR !isset($this->product_price_customer_including_tax[$index])) {
            return;
        }

        if ((is_float($this->product_tax_percentage[$index]) OR is_numeric($this->product_tax_percentage[$index])) AND (is_float($this->product_price_customer_including_tax[$index]) OR is_numeric($this->product_price_customer_including_tax[$index]))) {
            $this->product_price_customer_excluding_tax[$index] = number_format(($this->product_price_customer_including_tax[$index] / (1 + ($this->product_tax_percentage[$index] / 100))), 2, '.', '');
        }
    }

    private function CalculateProductRevenue($index)
    {
        $product_price_customer_excluding_tax = isset($this->product_price_customer_excluding_tax[$index]) ? $this->product_price_customer_excluding_tax[$index] : null;
        $product_purchase_price_excluding_tax = isset($this->product_purchase_price_excluding_tax[$index]) ? $this->product_purchase_price_excluding_tax[$index] : null;
        $product_amount = isset($this->product_amount[$index]) ? $this->product_amount[$index] : null;
        $product_price_customer_including_tax = isset($this->product_price_customer_including_tax[$index]) ? $this->product_price_customer_including_tax[$index] : null;

        if ((is_float($product_price_customer_excluding_tax) OR is_numeric($product_price_customer_excluding_tax)) AND (is_float($product_purchase_price_excluding_tax) OR is_numeric($product_purchase_price_excluding_tax)) AND (is_float($product_amount) OR is_numeric($product_amount))) {
            $this->product_revenue[$index] = number_format(($product_price_customer_excluding_tax * $product_amount), 2, '.', '');
            $this->product_profit[$index] = number_format(($this->product_revenue[$index] - ($product_purchase_price_excluding_tax * $product_amount)), 2, '.', '');
            $this->total_purchase_price_excluding_tax_order_product[$index] = number_format(($product_purchase_price_excluding_tax * $product_amount), 2, '.', '');
        } else {
            $this->product_revenue[$index] = null;
            $this->product_profit[$index] = null;
            $this->total_purchase_price_excluding_tax_order_product[$index] = null;
        }

        if ((is_float($product_price_customer_including_tax) OR is_numeric($product_price_customer_including_tax)) AND (is_float($product_purchase_price_excluding_tax) OR is_numeric($product_purchase_price_excluding_tax)) AND (is_float($product_amount) OR is_numeric($product_amount))) {
            $this->product_total_price_customer_including_tax[$index] = number_format(($product_price_customer_including_tax * $product_amount), 2, '.', '');
        } else {
            $this->product_total_price_customer_including_tax[$index] = null;
        }   

        $this->CalculateOrderTotals();
    }

    public function removeHours($index)
    {
        if (isset($this->hour_id[$index])) {
            $this->order_hours_to_be_removed[] = $this->hour_id[$index];
        }
        unset($this->orderhours[$index], $this->hour_id[$index], $this->hour_name[$index], $this->hour_price_customer_excluding_tax[$index],$this->hour_price_customer_including_tax[$index], $this->hour_date[$index], $this->hour_start_time[$index], $this->hour_end_time[$index], $this->hour_amount[$index], $this->hour_amount_price_excluding_tax[$index], $this->hour_amount_price_including_tax[$index], $this->hour_tax_percentage[$index], $this->hour_description[$index], $this->hour_user[$index], $this->hour_edit[$index]);
    }

    public function addHours()
    {
        $this->orderhours[] = '';
        $this->hour_name[array_key_last($this->orderhours)] = '';
        $this->hour_user[array_key_last($this->orderhours)] = Auth::user()->id;
        $this->hour_date[array_key_last($this->orderhours)] = Carbon::now()->toDateString();
        $this->hour_tax_percentage[array_key_last($this->orderhours)] = $this->tax_types->where('default',1)->first()->percentage ?? 21;
        $this->hour_edit[array_key_last($this->orderhours)] = 1;
        $this->hour_price_customer_excluding_tax[array_key_last($this->orderhours)] = 0.00;
        $this->hour_price_customer_including_tax[array_key_last($this->orderhours)] = 0.00;
        $this->hour_start_time[array_key_last($this->orderhours)] = null;
        $this->hour_end_time[array_key_last($this->orderhours)] = null;
        $this->hour_amount[array_key_last($this->orderhours)] = 0;
        $this->hour_amount_price_excluding_tax[array_key_last($this->orderhours)] = 0.00;
        $this->hour_amount_price_including_tax[array_key_last($this->orderhours)] = 0.00;
        $this->hour_description[array_key_last($this->orderhours)] = null;
        $this->hour_kilometers[array_key_last($this->orderhours)] = null;
        $this->hour_time_minutes[array_key_last($this->orderhours)] = null;
    }

    public function updatedHourName($value, $index)
    {
        $hour_type = $this->hour_types->where('name', $value)->first();
        if ($hour_type) {
            $this->hour_price_customer_excluding_tax[$index] = number_format($hour_type->price_customer_excluding_tax, 2, '.', '');
            $this->hour_price_customer_including_tax[$index] = number_format($hour_type->price_customer_including_tax, 2, '.', '');
            $this->hour_tax_percentage[$index] = $hour_type->tax_percentage;
        }
        $this->CalculateHourAmount($index);
    }

    public function updatedHourStartTime($value, $index)
    {
        $this->CalculateHourAmount($index);
    }

    public function updatedHourEndTime($value, $index)
    {
        $this->CalculateHourAmount($index);
    }

    public function updatedHourPriceCustomerExcludingTax($value, $index)
    {
        $this->CalculateHourPriceCustomerIncludingTax($index);
        $this->CalculateHourAmount($index);
    }

    public function updatedHourPriceCustomerIncludingTax($value, $index)
    {
        $this->CalculateHourPriceCustomerExcludingTax($index);
        $this->CalculateHourAmount($index);
    }

    public function updatedHourTaxPercentage($value, $index)
    {
        $this->CalculateHourPriceCustomerIncludingTax($index);
        $this->CalculateHourAmount($index);
    }

    private function CalculateHourPriceCustomerExcludingTax($index)
    {
        if (!isset($this->hour_tax_percentage[$index]) OR !isset($this->hour_price_customer_including_tax[$index])) {
            return;
        }
        
        if (is_numeric($this->hour_price_customer_including_tax[$index]) AND is_numeric($this->hour_tax_percentage[$index])) {
            $this->hour_price_customer_excluding_tax[$index] = number_format(($this->hour_price_customer_including_tax[$index] / (1 + ($this->hour_tax_percentage[$index] / 100))), 2, '.', '');
        }
    }

    private function CalculateHourPriceCustomerIncludingTax($index)
    {
        if (!isset($this->hour_tax_percentage[$index]) OR !isset($this->hour_price_customer_excluding_tax[$index])) {
            return;
        }
        
        if (is_numeric($this->hour_price_customer_excluding_tax[$index]) AND is_numeric($this->hour_tax_percentage[$index])) {
            $this->hour_price_customer_including_tax[$index] = number_format(($this->hour_price_customer_excluding_tax[$index] * (1 + ($this->hour_tax_percentage[$index] / 100))), 2, '.', '');
        }
    }

    public function CalculateHourAmount($index)
    {
        $hour_start_time = isset($this->hour_start_time[$index]) ? $this->hour_start_time[$index] : null;
        $hour_end_time = isset($this->hour_end_time[$index]) ? $this->hour_end_time[$index] : null;
        $hour_price_customer_excluding_tax = isset($this->hour_price_customer_excluding_tax[$index]) ? $this->hour_price_customer_excluding_tax[$index] : null;
        $hour_price_customer_including_tax = isset($this->hour_price_customer_including_tax[$index]) ? $this->hour_price_customer_including_tax[$index] : null;

        if ($hour_start_time !== null AND $hour_end_time !== null) {
            // Calculate hours worked
            $this->hour_amount[$index] = (strtotime($hour_end_time) - strtotime($hour_start_time))/60/60;
            if ($this->hour_amount[$index] < 0) {
                $this->hour_amount[$index] = 24 + $this->hour_amount[$index];
            }

            if ($hour_price_customer_excluding_tax !== null) {
                $this->hour_amount_price_excluding_tax[$index] = number_format(($this->hour_amount[$index] * $hour_price_customer_excluding_tax), 2, '.', '');
            }

            if ($hour_price_customer_including_tax !== null) {
                $this->hour_amount_price_including_tax[$index] = number_format(($this->hour_amount[$index] * $hour_price_customer_including_tax), 2, '.', '');
            }

            $this->hour_amount[$index] = number_format($this->hour_amount[$index], 2, '.', '');

        } else {
            $this->hour_amount[$index] = null;
            $this->hour_amount_price_excluding_tax[$index] = null;
            $this->hour_amount_price_including_tax[$index] = null;
        }

        $this->CalculateOrderTotals();
    }

    private function CalculateOrderTotals()
    {
        $array_total_price_customer_excluding_tax = [];
        $array_total_tax_amount = [];
        $array_total_price_customer_including_tax = [];
        $array_total_purchase_price_excluding_tax = [];
        $array_total_profit = [];

        // Loop over all order products
        foreach ($this->orderproducts as $index => $orderproduct) {
            // Check if variables exist, else set to null
            $product_revenue = isset($this->product_revenue[$index]) ? $this->product_revenue[$index] : null;
            $product_revenue_including_tax = isset($this->product_revenue[$index]) ? $this->product_revenue[$index] * (1 + ($this->product_tax_percentage[$index] / 100)) : null;
            $product_profit = isset($this->product_profit[$index]) ? $this->product_profit[$index] : null;
            $product_purchase_price_excluding_tax = isset($this->product_purchase_price_excluding_tax[$index]) ? $this->product_purchase_price_excluding_tax[$index] : null;
            $product_amount = isset($this->product_amount[$index]) ? $this->product_amount[$index] : null;
            
            // Check if variables are float or numeric, if not skip
            if (
                (is_float($product_revenue) OR is_numeric($product_revenue)) AND 
                (is_float($product_revenue_including_tax) OR is_numeric($product_revenue_including_tax)) AND 
                (is_float($product_profit) OR is_numeric($product_profit)) AND 
                (is_float($product_purchase_price_excluding_tax) OR is_numeric($product_purchase_price_excluding_tax)) AND 
                (is_float($product_amount) OR is_numeric($product_amount)))
            {
                // For each product add prices including and excluding tax to an array
                $array_total_price_customer_excluding_tax[] = number_format((float)$product_revenue, 2, '.', '');
                $array_total_tax_amount[] = number_format((float)$product_revenue_including_tax - (float)$product_revenue, 2, '.', '');
                $array_total_price_customer_including_tax[] = number_format((float)$product_revenue_including_tax, 2, '.', '');
                $array_total_purchase_price_excluding_tax[] = number_format((float)$product_purchase_price_excluding_tax * (float)$product_amount, 2, '.', '');
                $array_total_profit[] = number_format((float)$product_profit, 2, '.', '');
            }
        }
        
        // Loop over all order hours
        foreach ($this->orderhours as $index => $order_hour) {
            // Check if variables exist, else set to null
            $hour_price_customer_excluding_tax = isset($this->hour_amount_price_excluding_tax[$index]) ? $this->hour_amount_price_excluding_tax[$index] : null;
            $hour_price_customer_including_tax = isset($this->hour_amount_price_including_tax[$index]) ? $this->hour_amount_price_including_tax[$index] : null;
                
            // Check if variables are float or numeric, if not skip
            if (
                (is_float($hour_price_customer_excluding_tax) OR is_numeric($hour_price_customer_excluding_tax)) AND 
                (is_float($hour_price_customer_including_tax) OR is_numeric($hour_price_customer_including_tax))) 
            {
                // For each hour add prices including and excluding tax to an array
                $array_total_price_customer_excluding_tax[] = number_format((float)$this->hour_amount_price_excluding_tax[$index], 2, '.', '');
                $array_total_tax_amount[] = number_format((float)$this->hour_amount_price_including_tax[$index] - (float)$this->hour_amount_price_excluding_tax[$index], 2, '.', '');
                $array_total_price_customer_including_tax[] = number_format((float)$this->hour_amount_price_including_tax[$index], 2, '.', '');
                $array_total_purchase_price_excluding_tax[] = 0;
                $array_total_profit[] = number_format((float)$this->hour_amount_price_excluding_tax[$index], 2, '.', '');
            }
        }

        $this->total_price_customer_excluding_tax = number_format(array_sum($array_total_price_customer_excluding_tax), 2, '.', '');
        $this->total_tax_amount = number_format(array_sum($array_total_tax_amount), 2, '.', '');
        $this->total_price_customer_including_tax = number_format(array_sum($array_total_price_customer_including_tax), 2, '.', '');
        $this->total_purchase_price_excluding_tax = number_format(array_sum($array_total_purchase_price_excluding_tax), 2, '.', '');
        $this->total_profit = number_format(array_sum($array_total_profit), 2, '.', '');
    }

    public function reorder_product($index, $direction) {
        if ($direction == 'up' && ($this->orderproducts[$index] > min($this->orderproducts))) {
            $index_old_order = array_search($this->orderproducts[$index] - 1, $this->orderproducts);
            $this->orderproducts[$index] = $this->orderproducts[$index] - 1;
            $this->orderproducts[$index_old_order] = $this->orderproducts[$index_old_order] + 1;
        }
        
        if ($direction == 'down' && ($this->orderproducts[$index] < max($this->orderproducts))) {
            $index_old_order = array_search($this->orderproducts[$index] + 1, $this->orderproducts);
            $this->orderproducts[$index] = $this->orderproducts[$index] + 1;
            $this->orderproducts[$index_old_order] = $this->orderproducts[$index_old_order] - 1;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function store()
    {
        $this->validate();

        try{
            DB::transaction(function () {

                // Calculate product revenue for all order products
                foreach ($this->orderproducts as $index => $orderproduct) {
                    $this->CalculateProductRevenue($index);
                }

                // Calcualte hour amounts for all order hours
                foreach ($this->orderhours as $index => $order_hour) {
                    $this->CalculateHourAmount($index);
                }

                // Calculate order totals 
                $this->CalculateOrderTotals();

                // Save or update order information
                $order = Order::updateOrCreate(
                    ['id' => $this->modelId],
                    [
                        'title' => $this->title,
                        'description' => trim($this->description, " \n\r\t\v\0"),
                        'customer_id' => $this->customer,
                        'order_status_id' => $this->status,
                        'created_at' => $this->created_at ?? Carbon::now(),
                        'total_price_customer_excluding_tax' => $this->total_price_customer_excluding_tax,
                        'total_tax_amount' => $this->total_tax_amount,
                        'total_price_customer_including_tax' => $this->total_price_customer_including_tax,
                        'total_purchase_price_excluding_tax' => $this->total_purchase_price_excluding_tax,
                        'total_profit' => $this->total_profit,
                    ]
                );

                // Set modelId to Order ID
                $this->modelId = $order->id;

                // Remove order products that are removed
                if (count($this->order_products_to_be_removed) > 0) {
                    foreach ($this->order_products_to_be_removed as $index => $order_product_to_be_removed) {
                        OrderProduct::destroy($order_product_to_be_removed);
                    }
                }

                // Remove order hours that are removed
                if (count($this->order_hours_to_be_removed) > 0) {
                    foreach ($this->order_hours_to_be_removed as $index => $order_hour_to_be_removed) {
                        OrderHour::destroy($order_hour_to_be_removed);
                    }
                }

                // Add each order product to the order
                foreach ($this->orderproducts as $index => $product_order) {
                    // Set variables
                    $product_id = isset($this->product_id[$index]) ? $this->product_id[$index] : null;
                    $product_supplier = isset($this->product_supplier[$index]) ? $this->product_supplier[$index] : null;
                    $product_order_number = isset($this->product_order_number[$index]) ? $this->product_order_number[$index] : null;
                    $product_created_at = isset($this->product_created_at[$index]) ? $this->product_created_at[$index] : Carbon::now();
                    $product_user = isset($this->product_user[$index]) ? $this->product_user[$index] : null;
                    $product_description = isset($this->product_description[$index]) ? $this->product_description[$index] : null;
                    if ($this->product_edit[$index] == 0) {
                        continue;
                    }

                    // Update or create order product
                    OrderProduct::updateOrCreate([
                        'id' => $product_id
                    ],
                    [
                        'name' => $this->product_name[$index],
                        'supplier' => $product_supplier,
                        'order_number' => $product_order_number,
                        'user_id' => $product_user,
                        'purchase_price_excluding_tax' => $this->product_purchase_price_excluding_tax[$index],
                        'purchase_price_including_tax' => $this->product_purchase_price_including_tax[$index],
                        'price_customer_excluding_tax' => $this->product_price_customer_excluding_tax[$index],
                        'price_customer_including_tax' => $this->product_price_customer_including_tax[$index],
                        'amount' => $this->product_amount[$index],
                        'revenue' => $this->product_revenue[$index],
                        'profit' => $this->product_profit[$index],
                        'total_price_customer_including_tax' => $this->product_total_price_customer_including_tax[$index],
                        'tax_percentage' => $this->product_tax_percentage[$index],
                        'description' => trim($product_description, " \n\r\t\v\0"),
                        'order_id' => $order->id,
                        'total_purchase_price_excluding_tax' => $this->total_purchase_price_excluding_tax_order_product[$index],
                        'created_at' => $product_created_at,
                        'order' => $product_order,
                    ]);
                }

                // Add each order hour to the order
                foreach ($this->orderhours as $index => $order_hour) {
                    // Set variables
                    $hour_id = isset($this->hour_id[$index]) ? $this->hour_id[$index] : null;
                    $hour_kilometers = isset($this->hour_kilometers[$index]) ? $this->hour_kilometers[$index] : null;
                    $hour_time_minutes = isset($this->hour_time_minutes[$index]) ? $this->hour_time_minutes[$index] : null;
                    if ($this->hour_edit[$index] == 0) {
                        continue;
                    }

                    // Update or create order hours
                    OrderHour::updateOrCreate([
                        'id' => $hour_id
                    ],
                    [
                        'name' => $this->hour_name[$index],
                        'user_id' => $this->hour_user[$index],
                        'price_customer_excluding_tax' => $this->hour_price_customer_excluding_tax[$index],
                        'price_customer_including_tax' => $this->hour_price_customer_including_tax[$index],
                        'date' => $this->hour_date[$index],
                        'start_time' => $this->hour_start_time[$index],
                        'end_time' => $this->hour_end_time[$index],
                        'amount' => $this->hour_amount[$index],
                        'amount_revenue_excluding_tax' => $this->hour_amount_price_excluding_tax[$index],
                        'amount_revenue_including_tax' => $this->hour_amount_price_including_tax[$index],
                        'tax_percentage' => $this->hour_tax_percentage[$index],
                        'kilometers' => $hour_kilometers,
                        'time_minutes' => $hour_time_minutes,
                        'description' => trim($this->hour_description[$index], " \n\r\t\v\0"),
                        'order_id' => $order->id,
                    ]);
                }
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De opdracht is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('order.index'));
    }

    public function editNotAllowed()
    {
        return $this->dispatch('swal:modal', [
            'type' => 'warning',
            'title' => 'Je kunt dit product niet bewerken',
            'text' => 'Dit product zit gekoppeld aan een factuur en kan daarom niet bewerkt worden.',
        ]);
    }

    public function ProductSelector($index)
    {
        $this->dispatch('getIndexValue', $index)->to('order.product-select');
        $this->dispatch('openProductSelectorModal');
    }

    public function downloadFile($itemID)
    {
        $file = Invoice::findOrFail($itemID)->getMedia('invoice')->first();
        return response()->download($file->getPath(), $file->file_name);
    }

    public function render()
    {
        return view('content.order.livewire.form');
    }
}
