<?php

namespace App\Livewire\Quote;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Customer,
    QuoteStatus,
    Product,
    QuoteProduct,
    Quote,
    Order,
    OrderProduct,
    OrderStatus,
    ProductGroup,
    SelectedQuoteProduct,
    TaxType,
    Media,
    Setting,
};

use Auth;
use Carbon\Carbon;
use Livewire\WithFileUploads;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Form extends Component
{
    use WithFileUploads;
    
    // General
    public $edit = 1;
    public $users, $tax_types;
    public $show_party_fields = 0;
    // Quote
    public $title, $customer, $status = '', $status_comment, $description, $expiration_date, $quote_text, $show_product_group_images = true, $show_amount_and_total = true, $updated_at, $created_at, $modelId;
    public $customers, $quotestatus;
    public $collapsed_view = false, $show_packages = true, $package_image, $new_package_image;
    // Party fields
    public $party_date, $location_array = [], $location = "", $party_type_array = [], $party_type = "", $start_time, $end_time, $guest_amount;
    // Quote Product Groups
    public $product_groups, $selected_product_groups = [];
    // Quote Product
    public $products, $quoteproducts = [];
    public $product_id, $product_extra_option, $product_name, $product_purchase_price_excluding_tax, $product_purchase_price_including_tax, $product_price_customer_excluding_tax, $product_price_customer_including_tax, $product_amount, $product_total_excluding_tax, $product_total_including_tax, $product_tax_percentage, $product_description, $product_description_count, $product_show_images, $product_highlight_text;
    public $product_updated_at, $product_created_at;
    public $quote_products_to_be_removed = [];
    // Cache and tax
    public $flush_cache = false, $prices_exclude_tax = false;
    // Previous status
    public $previous_quote_status;
    public $previous_quote_statuses = [];
    // Selected Products
    public $selected_quote_products = [], $selected_quote_products_to_be_removed = [];
    // Images
    public $images, $new_images, $image_id, $image_link, $image_path, $image_order, $images_to_be_removed, $image_copy;
    // Discount prices
    public $use_discount_prices, $total_discount_price_customer_excluding_tax, $total_discount_price_customer_including_tax, $discount_price_customer_excluding_tax, $discount_price_customer_including_tax;

    public function mount($model, $edit)
    {
        $this->customers = Customer::orderBy('name')->get();
        $this->quotestatus = QuoteStatus::orderBy('order')->get();
        $this->products = Product::orderBy('name')->get();
        $this->product_groups = ProductGroup::orderBy('order')->orderBy('name')->get();
        $this->tax_types = TaxType::orderBy('default','DESC')->orderBy('percentage','DESC')->get();
        $this->quote_text = Setting::where('name','quote_text')->first()->value;
        $this->expiration_date = Carbon::now()->addDays(14)->format('Y-m-d');
        $this->show_amount_and_total = Setting::where('name','default_show_amount_and_total_for_quote')->first()->value == 1 ? true : false;
        $this->show_party_fields = Setting::where('name','enable_party_fields')->first()->value == 1 ? true : false;
        $this->status = $this->quotestatus->where('name', 'Nog maken')->first()->id ?? '';
        $this->party_type_array = [
            'Bruiloft',
            'Verjaardagsfeest',
            'Jubileumfeest',
            'Bedrijfsfeest',
            'Evenement',
            'Festival',
            'Overig',
        ];
        $this->location_array = [
            'Veghel',
            'Geldrop',
            'Sint-Oedenrode',
            'Schijndel',
            'Erp',
            'Boerdonk',
            'Boskant',
            'Eerde',
            'Keldonk',
            'Mariaheide',
            'Nijnsel',
            'Wijbosch',
            'Zijtaart',
            'Uden',
            'Schaijk',
            'Zeeland',
            'Volkel',
            'Odiliapeel',
            'Reek',
            'Heesch',
            'Heeswijk-Dinther',
            'Loosbroek',
            'Nistelrode',
            'Vinkel',
            'Vorstenbosch',
            'Langenboom',
            'Wilbertoord',
            'Mill',
            'Herpen',
            'Ravenstein',
            'Rijkevoort',
            'Den bosch',
            'Nuland',
            'Rosmalen',
            'Boxtel',
            'Liempde',
            'Esch',
            'Vught',
            'Helvoirt',
            'Cromvoirt',
            'Sint-Michielsgestel',
            'Berlicum',
            'Den Dungen',
            'Gemonde',
            'Maaskantje',
            'Middelrode',
            'Vlijmen',
            'Oss',
            'Grave',
            'Berghem',
            'Elshout',
            'Nieuwkuijk',
            'Haarsteeg',
            'Hedel',
            'Geffen',
            'Eindhoven',
            'Son en Breugel',
            'Best',
            'Nuenen',
            'Helmond',
            'Stiphout',
            'Oirschot',
            'Gerwen',
            'Nederwetten',
            'Gemert',
            'Bakel',
            'De Mortel',
            'Elsendorp',
            'Handel',
            'Milheeze',
            'Boekel',
            'Beek en Donk',
            'Milheeze',
            'Venhorst',
            'Huize Padua',
            'Mariahout',
            'Tilburg',
            'Oisterwijk',
            'Waalwijk',
            'Moergestel',
            'Berkel Enschot',
            'Kaatsheuvel',
            'Udenhout',
            'Haaren',
            'Drunen',
            'Biezenmortel',
            'Loon op Zand',
            'Cuijk',
            'Milsbeek',
            'Gennep',
            'Heijen',
            'Groesbeek',
            'Malden',
            'Mook',
            'Beers',
            'Overasselt',
            'Heumen',
            'Wijchen',
            'Nijmegen',
            'Beuningen',
            'Ewijk',
            'Berg en Dal',
            'Lent',
        ];
        sort($this->location_array);
        array_unshift($this->location_array,'De plaats staat niet in de lijst');
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit === 0) {
                $this->created_at = (date("Y-m-d",strtotime($model->created_at)));
                $this->updated_at = (date("Y-m-d",strtotime($model->updated_at)));
            }
            $this->title = $model->title;
            $this->description = $model->description;
            $this->expiration_date = Carbon::parse($model->expiration_date)->format('Y-m-d');
            $this->quote_text = $model->quote_text;
            $this->status = $model->quote_statuses->first()->id;
            $this->status_comment = $model->quote_statuses->first()->pivot->comment;
            $this->customer = $model->customer->id;
            $this->prices_exclude_tax = ($model->prices_exclude_tax == 1 ? true : false);
            $this->show_product_group_images = ($model->show_product_group_images == 1 ? true : false);
            $this->show_packages = ($model->show_packages == 1 ? true : false);
            $this->show_amount_and_total = ($model->show_amount_and_total == 1 ? true : false);
            $this->package_image = $model->getMedia('packages')->count() > 0 ? $model->getMedia('packages')->first()->getUrl() : null;
            $this->party_date = (!empty($model->party_date) ? Carbon::parse($model->party_date)->format('Y-m-d') : null);
            $this->location = $model->location ?? "";
            $this->party_type = $model->party_type ?? "";
            $this->start_time = substr($model->start_time, 0, 5);
            $this->end_time = substr($model->end_time, 0, 5);
            $this->guest_amount = $model->guest_amount;
            foreach ($model->quote_products as $index => $quote_product) {
                $this->quoteproducts[] = $quote_product->order ?? (is_array($this->quoteproducts) && !empty($this->quoteproducts) ? max($this->quoteproducts) + 1 : 1);
                $this->product_id[$index] = $quote_product->id;
                if (count($this->products->where('name', $quote_product->name)) === 0) {
                    $this->product_extra_option[$index] = $quote_product->name;
                } else {
                    $this->product_extra_option[$index] = '';
                }
                $this->product_name[$index] = $quote_product->name;
                $this->product_purchase_price_excluding_tax[$index] = $quote_product->purchase_price_excluding_tax;
                $this->product_purchase_price_including_tax[$index] = $quote_product->purchase_price_including_tax;
                $this->product_price_customer_excluding_tax[$index] = $quote_product->price_customer_excluding_tax;
                $this->product_price_customer_including_tax[$index] = $quote_product->price_customer_including_tax;
                $this->product_amount[$index] = $quote_product->amount;
                $this->product_total_excluding_tax[$index] = $quote_product->total_price_customer_excluding_tax;
                $this->product_total_including_tax[$index] = $quote_product->total_price_customer_including_tax;
                $this->product_tax_percentage[$index] = $quote_product->tax_percentage;
                $this->product_description[$index] = $quote_product->description;
                $this->product_show_images[$index] = ($quote_product->show_product_images == 1 ? true : false);
                $this->product_description_count[$index] = count(preg_split("/\n|\r\n/", $this->product_description[$index])) > 3 ? count(preg_split("/\n|\r\n/", $this->product_description[$index])) : 3;
                $this->images[$index] = [];
                $this->new_images[$index] = [];
                $this->image_order[$index] = [];
                $this->images_to_be_removed[$index] = [];
                $this->product_highlight_text[$index] = $quote_product->highlight_text;
                $this->use_discount_prices[$index] = ($quote_product->use_discount_prices == 1 ? true : false);
                $this->total_discount_price_customer_including_tax[$index] = $quote_product->total_discount_price_customer_including_tax ?? 0.00;
                $this->total_discount_price_customer_excluding_tax[$index] = $quote_product->total_discount_price_customer_excluding_tax ?? 0.00;
                $this->discount_price_customer_including_tax[$index] = $quote_product->discount_price_customer_including_tax ?? 0.00;
                $this->discount_price_customer_excluding_tax[$index] = $quote_product->discount_price_customer_excluding_tax ?? 0.00;
                foreach ($quote_product->getMedia('product_images') as $index_image => $image) {
                    $this->images[$index][] = '';
                    $this->new_images[$index] = [];
                    $this->image_id[$index][$index_image] = $image->id;
                    $this->image_order[$index][$index_image] = $image->order_column;
                    $this->image_link[$index][$index_image] = $image->getUrl();
                    
                }
                if ($edit === 0) {
                    $this->product_updated_at[$index] = $quote_product->updated_at->format('d-m-Y | H:i');
                    $this->product_created_at[$index] = $quote_product->created_at->format('d-m-Y | H:i');
                }
            }
            foreach ($model->product_groups as $product_group) {
                $this->selected_product_groups[$product_group->id] = true;
            }
            if ($edit === 0) {
                $this->previous_quote_statuses = $model->quote_statuses->toArray();
            }
            $this->selected_quote_products = $model->selected_quote_products;
        }

    }

    #[On('refreshForm')]
    public function refreshForm()
    {
        $this->products = Product::orderBy('name')->get();
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|min:2|max:255',
            'description' => 'sometimes|nullable|string',
            'expiration_date' => 'required|date',
            'quote_text' => 'sometimes|nullable|string',
            'customer' => 'required|integer|exists:customers,id',
            'status' => 'required|integer|exists:quote_statuses,id',
            'status_comment' => 'sometimes|nullable|string',
            'show_product_group_images' => 'required|boolean',
            'show_amount_and_total' => 'required|boolean',
            'show_packages' => 'required|boolean|max:1',
            'new_package_image' => 'nullable|image|mimes:png,jpg,jpeg|max:10240|dimensions:min_width=50,min_height=50',

            'party_date' => 'nullable|date_format:Y-m-d',
            'location' => 'nullable|string|in:' . implode(',', $this->location_array),
            'party_type' => 'nullable|string|in:' . implode(',', $this->party_type_array),
            'start_time' => ['nullable', 'date_format:H:i', function($attribute, $value, $fail) {
                $parts = explode(':', $value);
                if (count($parts) != 2 || intval($parts[1]) % 5 !== 0) {
                    $fail('De starttijd moet binnen een interval van 5 minuten vallen.');
                }
            }],
            'end_time' => ['nullable', 'date_format:H:i', function($attribute, $value, $fail) {
                $parts = explode(':', $value);
                if (count($parts) != 2 || intval($parts[1]) % 5 !== 0) {
                    $fail('De eindtijd moet binnen een interval van 5 minuten vallen.');
                }
            }],
            'guest_amount' => 'nullable|numeric',

            'product_name.*' => 'required|string|max:255',
            'product_purchase_price_excluding_tax.*' => 'required|numeric|between:-99999.99,99999.99',
            'product_purchase_price_including_tax.*' => 'required|numeric|between:-99999.99,99999.99',
            'product_price_customer_excluding_tax.*' => 'required|numeric|between:-99999.99,99999.99',
            'product_price_customer_including_tax.*' => 'required|numeric|between:-99999.99,99999.99',
            'product_amount.*' => 'required|numeric|between:-99.99,99.99',
            'product_total_excluding_tax.*' => 'required|numeric|between:-99999999.99,99999999.99',
            'product_total_including_tax.*' => 'required|numeric|between:-99999999.99,99999999.99',
            'product_tax_percentage.*' => 'required|numeric|between:0,100',
            'product_description.*' => 'sometimes|nullable|string',
            'product_show_images.*' => 'required|boolean',
            'product_highlight_text.*' => 'sometimes|nullable|string',
            
            'new_images.*' => 'array|max:12',
            'new_images.*.*' => 'nullable|image|mimes:png,jpg,jpeg|max:10240|dimensions:min_width=50,min_height=50',
            'images.*' => 'array',

            'selected_product_groups' => 'array',

            'flush_cache' => 'nullable|boolean|max:1',
            'prices_exclude_tax' => 'nullable|boolean|max:1',

            'discount_price_customer_excluding_tax.*' => 'required|numeric|between:-99999999.99,99999999.99',
            'discount_price_customer_including_tax.*' => 'required|numeric|between:-99999999.99,99999999.99',
            'total_discount_price_customer_excluding_tax.*' => 'required|numeric|between:-99999999.99,99999999.99',
            'total_discount_price_customer_including_tax.*' => 'required|numeric|between:-99999999.99,99999999.99',
            'use_discount_prices.*' => 'required|boolean',
        ];
    }

    protected $validationAttributes = [
        'title' => 'titel',
        'description' => 'beschrijving',
        'expiration_date' => 'vervaldatum',
        'quote_text' => 'offerte tekst',
        'customer' => 'klant',
        'status' => 'status',
        'status_comment' => 'status commentaar',
        'show_product_group_images' => 'product groep afbeeldingen weergeven',
        'show_amount_and_total' => 'aantal en totaal weergeven',
        'show_packages' => 'pakketten weergeven',
        'new_package_image' => 'pakket afbeelding',

        'party_date' => 'feestdatum',
        'location' => 'Feestlocatie',
        'party_type' => 'feest type',
        'start_time' => 'starttijd',
        'end_time' => 'eindtijd',
        'guest_amount' => 'aantal gasten',

        'product_name.*' => 'product naam',
        'product_purchase_price_excluding_tax.*' => 'inkoopprijs excl. BTW',
        'product_purchase_price_including_tax.*' => 'inkoopprijs incl. BTW',
        'product_price_customer_excluding_tax.*' => 'klantprijs excl. BTW',
        'product_price_customer_excluding_tax.*' => 'klantprijs incl. BTW',
        'product_amount.*' => 'aantal',
        'product_total_excluding_tax.*' => 'Totaal excl. BTW',
        'product_total_including_tax.*' => 'Totaal incl. BTW',
        'product_tax_percentage.*' => 'belasting percentage',
        'product_description.*' => 'product beschrijving',
        'product_show_images.*' => 'product afbeelding weergeven',
        'product_highlight_text.*' => 'text uitgelicht',
        
        'new_images.*' => 'nieuwe afbeeldingen',
        'new_images.*.*' => 'afbeelding',
        'images.*' => 'afbeeldingen',
        'product.order' => 'volgorde',

        'selected_product_groups' => 'geselecteerde product groepen',
        'selected_product_groups.*' => 'product groep',

        'flush_cache' => 'verwijder cache',
        'prices_exclude_tax' => 'prijzen zijn exclusief BTW',

        'discount_price_customer_excluding_tax.*' => 'kortingsprijs exclusief btw',
        'discount_price_customer_including_tax.*' => 'kortingsprijs inclusief btw',
        'total_discount_price_customer_excluding_tax.*' => 'totale kortingsprijs exclusief btw',
        'total_discount_price_customer_including_tax.*' => 'totale kortingsprijs inclusief btw',
        'use_discount_prices.*' => 'gebruik kortingsprijzen',
    ];

    public function removeProduct($index)
    {
        if (isset($this->product_id[$index])) {
            $this->quote_products_to_be_removed[] = $this->product_id[$index];
        }

        asort($this->quoteproducts);

        unset($this->quoteproducts[$index], $this->product_id[$index], $this->product_extra_option[$index], $this->product_name[$index], $this->product_purchase_price_excluding_tax[$index], $this->product_purchase_price_including_tax[$index], $this->product_price_customer_excluding_tax[$index], $this->product_price_customer_including_tax[$index],$this->product_amount[$index], $this->product_total_excluding_tax[$index], $this->product_total_including_tax[$index], $this->product_tax_percentage[$index], $this->product_description[$index], $this->product_description_count[$index], $this->product_show_images[$index],
        $this->images[$index], $this->new_images[$index], $this->image_id[$index], $this->image_link[$index], $this->image_path[$index], $this->image_order[$index], $this->images_to_be_removed[$index], $this->image_copy[$index], $this->product_highlight_text[$index], $this->discount_price_customer_excluding_tax[$index], $this->discount_price_customer_including_tax[$index], $this->total_discount_price_customer_excluding_tax[$index], $this->total_discount_price_customer_including_tax[$index], $this->use_discount_prices[$index]);

        $order = 1;
        foreach ($this->quoteproducts as $index => $product_order) { 
            $this->quoteproducts[$index] = $order++;
        }
    }

    public function addProduct()
    {
        $this->quoteproducts[] = (is_array($this->quoteproducts) && !empty($this->quoteproducts) ? max($this->quoteproducts) + 1 : 1);
        $this->product_extra_option[] = '';
        $this->product_name[array_key_last($this->quoteproducts)] = '';
        $this->product_highlight_text[array_key_last($this->quoteproducts)] = '';
        $this->product_description_count[] = 3;
        $this->product_tax_percentage[array_key_last($this->quoteproducts)] = $this->tax_types->where('default',1)->first()->percentage ?? 21;
        $this->product_show_images[] = true;
        $this->images[array_key_last($this->quoteproducts)] = [];
        $this->new_images[array_key_last($this->quoteproducts)] = [];
        $this->image_order[array_key_last($this->quoteproducts)] = [];
        $this->images_to_be_removed[array_key_last($this->quoteproducts)] = [];
        $this->use_discount_prices[array_key_last($this->quoteproducts)] = false;
        $this->discount_price_customer_excluding_tax[array_key_last($this->quoteproducts)] = 0.00;
        $this->discount_price_customer_including_tax[array_key_last($this->quoteproducts)] = 0.00;
        $this->total_discount_price_customer_excluding_tax[array_key_last($this->quoteproducts)] = 0.00;
        $this->total_discount_price_customer_including_tax[array_key_last($this->quoteproducts)] = 0.00;
        $this->product_amount[array_key_last($this->quoteproducts)] = 1;
        $this->product_total_excluding_tax[array_key_last($this->quoteproducts)] = 0.00;
        $this->product_total_including_tax[array_key_last($this->quoteproducts)] = 0.00;
        $this->product_purchase_price_including_tax[array_key_last($this->quoteproducts)] = 0.00;
        $this->product_price_customer_excluding_tax[array_key_last($this->quoteproducts)] = 0.00;
        $this->product_price_customer_including_tax[array_key_last($this->quoteproducts)] = 0.00;
        $this->product_purchase_price_excluding_tax[array_key_last($this->quoteproducts)] = 0.00;
        $this->product_description[array_key_last($this->quoteproducts)] = null;
        $this->product_created_at[array_key_last($this->quoteproducts)] = null;
        $this->product_updated_at[array_key_last($this->quoteproducts)] = null;
    }

    public function removeImage($index, $index_image)
    {
        if (isset($this->image_id[$index][$index_image])) {
            $this->images_to_be_removed[$index][] = $this->image_id[$index][$index_image];
        }
        unset($this->images[$index][$index_image], $this->image_id[$index][$index_image], $this->image_link[$index][$index_image], $this->image_order[$index][$index_image], $this->image_path[$index][$index_image]);

        if (isset($image_copy[$index][$index_image])) {
            unset($image_copy[$index][$index_image]);
        }
        $this->flush_cache = true;
    }

    public function removeSelectedQuoteProduct($id)
    {
        $this->quote_products_to_be_removed[] = $id;
        $this->selected_quote_products = $this->selected_quote_products->except($id);
    }

    public function updatedNewImages($images, $index)
    {
        foreach ($images as $image) {
            $this->images[$index][] = '';
            $this->image_order[$index][array_key_last($this->images[$index])] = count($this->image_order[$index]) + 1;
            $this->image_link[$index][array_key_last($this->images[$index])] = $image->temporaryUrl();
            $this->image_path[$index][array_key_last($this->images[$index])] = $image->path();
        }
        $this->flush_cache = true;
    }

    #[On('orderUpdated')]
    public function orderUpdated($order)
    {
        foreach ($order as $group)
        {
            $index = (int)$group['value'];
            foreach ($group['items'] as $item) {
                $order = (int)$item['order'];
                $index_image = (int)$item['value'];
                $this->image_order[$index][$index_image] = $order;
            }
            asort($this->image_order[$index]);
        }
        $this->flush_cache = true;
    }

    public function updatedProductShowImages()
    {
        $this->flush_cache = true;
    }

    #[On('productSelected')]
    public function productSelected($id, $index)
    {
        $this->flush_cache = true;
        
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
            $this->use_discount_prices[$index] = $product->use_discount_price;
            $this->discount_price_customer_excluding_tax[$index] = number_format($product->discount_price_customer_excluding_tax, 2, '.', '');
            $this->discount_price_customer_including_tax[$index] = number_format($product->discount_price_customer_including_tax, 2, '.', '');

            foreach ($product->getMedia('product_images') as $index_image => $media) {
                $this->images[$index][] = '';
                $this->image_order[$index][array_key_last($this->images[$index])] = count($this->image_order[$index]) + 1;
                $this->image_copy[$index][array_key_last($this->images[$index])] = $media->id;
                $this->image_link[$index][array_key_last($this->images[$index])] = $media->getUrl();
            }
            
        }

        if (empty($this->product_amount[$index])) {
            $this->product_amount[$index] = number_format(1, 2, '.', '');
        }
        $this->CalculateProductTotalExcludingTax($index);
        $this->CalculateProductTotalIncludingTax($index);
        $this->CalculateProductDiscountTotalExcludingTax($index);
        $this->CalculateProductDiscountTotalIncludingTax($index);
    }

    public function updatedProductPurchasePriceExcludingTax($value, $index)
    {
        $this->flush_cache = true;
        if (is_numeric($this->product_purchase_price_excluding_tax[$index])) {
            $this->product_purchase_price_excluding_tax[$index] = floatval(number_format($value, 2, '.', ''));
        }
        $this->CalculatePurchasePriceIncludingTax($index);
    }

    public function updatedProductPurchasePriceIncludingTax($value, $index)
    {
        $this->flush_cache = true;
        if (is_numeric($this->product_purchase_price_including_tax[$index])) {
            $this->product_purchase_price_including_tax[$index] = floatval(number_format($value, 2, '.', ''));
        }
        $this->CalculatePurchasePriceExcludingTax($index);
    }

    public function updatedProductPriceCustomerExcludingTax($value, $index)
    {
        $this->flush_cache = true;
        if (is_numeric($this->product_price_customer_excluding_tax[$index])) {
            $this->product_price_customer_excluding_tax[$index] = floatval(number_format($value, 2, '.', ''));
        }
        $this->CalculateProductPriceCustomerIncludingTax($index);
        $this->CalculateProductTotalExcludingTax($index);
    }

    public function updatedProductPriceCustomerIncludingTax($value, $index)
    {
        $this->flush_cache = true;
        if (is_numeric($this->product_price_customer_including_tax[$index])) {
            $this->product_price_customer_including_tax[$index] = floatval(number_format($value, 2, '.', ''));
        }
        $this->CalculateProductPriceCustomerExcludingTax($index);
        $this->CalculateProductTotalIncludingTax($index);
    }

    public function updatedProductAmount($value, $index)
    {
        $this->flush_cache = true;
        if (is_numeric($this->product_amount[$index])) {
            $this->product_amount[$index] = floatval(number_format($value, 2, '.', ''));
        }
        $this->CalculateProductTotalExcludingTax($index);
        $this->CalculateProductTotalIncludingTax($index);
        if ($this->use_discount_prices[$index] == true) {
            $this->CalculateProductDiscountTotalExcludingTax($index);
            $this->CalculateProductDiscountTotalIncludingTax($index);
        }
    }

    public function updatedProductTaxPercentage($value, $index)
    {
        $this->flush_cache = true;
        if (is_numeric($this->product_tax_percentage[$index])) {
            $this->product_tax_percentage[$index] = floatval(number_format($value, 2, '.', ''));
        }
        $this->CalculateProductPriceCustomerIncludingTax($index);
        if ($this->use_discount_prices[$index] == true) {
            $this->CalculateProductDiscountPriceCustomerIncludingTax($index);
        }
    }

    public function updatedDiscountPriceCustomerExcludingTax($value, $index)
    {
        $this->flush_cache = true;
        if ($value != null && $value != '' && is_numeric($this->discount_price_customer_excluding_tax[$index])) {
            $this->discount_price_customer_excluding_tax[$index] = floatval(number_format($value, 2, '.', ''));
        }
        $this->CalculateProductDiscountPriceCustomerIncludingTax($index);
        $this->CalculateProductDiscountTotalExcludingTax($index);
    }

    public function updatedDiscountPriceCustomerIncludingTax($value, $index)
    {
        $this->flush_cache = true;
        if ($value != null && $value != '' && is_numeric($this->discount_price_customer_including_tax[$index])) {
            $this->discount_price_customer_including_tax[$index] = floatval(number_format($value, 2, '.', ''));
        }
        $this->CalculateProductDiscountPriceCustomerExcludingTax($index);
        $this->CalculateProductDiscountTotalIncludingTax($index);
    }

    public function updatedUseDiscountPrices($value, $index)
    {
        $this->flush_cache = true;
        if ($value == false) {
            if ($this->discount_price_customer_excluding_tax[$index] == null OR $this->discount_price_customer_excluding_tax[$index] == '') {
                $this->discount_price_customer_excluding_tax[$index] = 0.00;
            }
            if ($this->discount_price_customer_including_tax[$index] == null OR $this->discount_price_customer_including_tax[$index] == '') {
                $this->discount_price_customer_including_tax[$index] = 0.00;
            }
        }
        $this->CalculateProductDiscountTotalIncludingTax($index);
        $this->CalculateProductDiscountTotalExcludingTax($index);
    }

    public function updatedProductName()
    {
        $this->flush_cache = true;
    }

    public function updatedProductDescription()
    {
        $this->flush_cache = true;
    }

    public function updatedProductHighlightText()
    {
        $this->flush_cache = true;
    }

    private function CalculatePurchasePriceIncludingTax($index)
    {
        if ((is_float($this->product_purchase_price_excluding_tax[$index]) OR is_numeric($this->product_purchase_price_excluding_tax[$index])) AND (is_float($this->product_tax_percentage[$index]) OR is_numeric($this->product_tax_percentage[$index]))) {
            $this->product_purchase_price_including_tax[$index] = number_format(($this->product_purchase_price_excluding_tax[$index] * (1 + ($this->product_tax_percentage[$index] / 100))), 2, '.', '');
        }
    }

    private function CalculatePurchasePriceExcludingTax($index)
    {
        if ((is_float($this->product_purchase_price_including_tax[$index]) OR is_numeric($this->product_purchase_price_including_tax[$index])) AND (is_float($this->product_tax_percentage[$index]) OR is_numeric($this->product_tax_percentage[$index]))) {
            $this->product_purchase_price_excluding_tax[$index] = number_format(($this->product_purchase_price_including_tax[$index] / (1 + ($this->product_tax_percentage[$index] / 100))), 2, '.', '');
        }
    }

    private function CalculateProductPriceCustomerIncludingTax($index)
    {
        if ((is_float($this->product_price_customer_excluding_tax[$index]) OR is_numeric($this->product_price_customer_excluding_tax[$index])) AND (is_float($this->product_tax_percentage[$index]) OR is_numeric($this->product_tax_percentage[$index]))) {
            $this->product_price_customer_including_tax[$index] = number_format(($this->product_price_customer_excluding_tax[$index] * (1 + ($this->product_tax_percentage[$index] / 100))), 2, '.', '');
        }
        $this->CalculateProductTotalIncludingTax($index);
    }

    private function CalculateProductPriceCustomerExcludingTax($index)
    {
        if ((is_float($this->product_price_customer_including_tax[$index]) OR is_numeric($this->product_price_customer_including_tax[$index])) AND (is_float($this->product_tax_percentage[$index]) OR is_numeric($this->product_tax_percentage[$index]))) {
            $this->product_price_customer_excluding_tax[$index] = number_format(($this->product_price_customer_including_tax[$index] / (1 + ($this->product_tax_percentage[$index] / 100))), 2, '.', '');
        }
        $this->CalculateProductTotalExcludingTax($index);
    }

    private function CalculateProductTotalExcludingTax($index)
    {
        $product_price_customer_excluding_tax = isset($this->product_price_customer_excluding_tax[$index]) ? $this->product_price_customer_excluding_tax[$index] : null;
        $product_amount = isset($this->product_amount[$index]) ? $this->product_amount[$index] : null;

        if ((is_float($product_price_customer_excluding_tax) OR is_numeric($product_price_customer_excluding_tax)) AND (is_float($product_amount) OR is_numeric($product_amount))) {
            $this->product_total_excluding_tax[$index] = number_format(($product_price_customer_excluding_tax * $product_amount), 2, '.', '');
        }
    }

    private function CalculateProductTotalIncludingTax($index)
    {
        $product_price_customer_including_tax = isset($this->product_price_customer_including_tax[$index]) ? $this->product_price_customer_including_tax[$index] : null;
        $product_amount = isset($this->product_amount[$index]) ? $this->product_amount[$index] : null;

        if ((is_float($product_price_customer_including_tax) OR is_numeric($product_price_customer_including_tax)) AND (is_float($product_amount) OR is_numeric($product_amount))) {
            $this->product_total_including_tax[$index] = number_format(($product_price_customer_including_tax * $product_amount), 2, '.', '');
        }
    }

    private function CalculateProductDiscountPriceCustomerIncludingTax($index)
    {
        if ((is_float($this->discount_price_customer_excluding_tax[$index]) OR is_numeric($this->discount_price_customer_excluding_tax[$index])) AND (is_float($this->product_tax_percentage[$index]) OR is_numeric($this->product_tax_percentage[$index]))) {
            $this->discount_price_customer_including_tax[$index] = number_format(($this->discount_price_customer_excluding_tax[$index] * (1 + ($this->product_tax_percentage[$index] / 100))), 2, '.', '');
        }
        $this->CalculateProductDiscountTotalIncludingTax($index);
    }

    private function CalculateProductDiscountPriceCustomerExcludingTax($index)
    {
        if ((is_float($this->discount_price_customer_including_tax[$index]) OR is_numeric($this->discount_price_customer_including_tax[$index])) AND (is_float($this->product_tax_percentage[$index]) OR is_numeric($this->product_tax_percentage[$index]))) {
            $this->discount_price_customer_excluding_tax[$index] = number_format(($this->discount_price_customer_including_tax[$index] / (1 + ($this->product_tax_percentage[$index] / 100))), 2, '.', '');
        }
        $this->CalculateProductDiscountTotalExcludingTax($index);
    }

    private function CalculateProductDiscountTotalExcludingTax($index)
    {
        $discount_price_customer_excluding_tax = isset($this->discount_price_customer_excluding_tax[$index]) ? $this->discount_price_customer_excluding_tax[$index] : null;
        $product_amount = isset($this->product_amount[$index]) ? $this->product_amount[$index] : null;

        if ((is_float($discount_price_customer_excluding_tax) OR is_numeric($discount_price_customer_excluding_tax)) AND (is_float($product_amount) OR is_numeric($product_amount))) {
            $this->total_discount_price_customer_excluding_tax[$index] = number_format(($discount_price_customer_excluding_tax * $product_amount), 2, '.', '');
        }
    }

    private function CalculateProductDiscountTotalIncludingTax($index)
    {
        $discount_price_customer_including_tax = isset($this->discount_price_customer_including_tax[$index]) ? $this->discount_price_customer_including_tax[$index] : null;
        $product_amount = isset($this->product_amount[$index]) ? $this->product_amount[$index] : null;

        if ((is_float($discount_price_customer_including_tax) OR is_numeric($discount_price_customer_including_tax)) AND (is_float($product_amount) OR is_numeric($product_amount))) {
            $this->total_discount_price_customer_including_tax[$index] = number_format(($discount_price_customer_including_tax * $product_amount), 2, '.', '');
        }
    }

    public function updatingSelectedProductGroups($value, $id)
    {
        $this->flush_cache = true;

        if ($value === false) {
            unset($this->selected_product_groups[$id]);
        }
    }

    public function updatedShowProductGroupImages()
    {
        $this->flush_cache = true;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedStatus()
    {
        $this->status_comment = null;
    }

    public function reorder_product($index, $direction) {
        if ($direction == 'up' && ($this->quoteproducts[$index] > min($this->quoteproducts))) {
            $index_old_order = array_search($this->quoteproducts[$index] - 1, $this->quoteproducts);
            $this->quoteproducts[$index] = $this->quoteproducts[$index] - 1;
            $this->quoteproducts[$index_old_order] = $this->quoteproducts[$index_old_order] + 1;
            $this->flush_cache = true;
        }
        
        if ($direction == 'down' && ($this->quoteproducts[$index] < max($this->quoteproducts))) {
            $index_old_order = array_search($this->quoteproducts[$index] + 1, $this->quoteproducts);
            $this->quoteproducts[$index] = $this->quoteproducts[$index] + 1;
            $this->quoteproducts[$index_old_order] = $this->quoteproducts[$index_old_order] - 1;
            $this->flush_cache = true;
        }
    }

    public function removeNewPackageImage()
    {
        $this->new_package_image = null;
    }

    public function removePackageImage()
    {
        $this->package_image = null;
    }

    public function store()
    {
        $this->validate();

        try{
            DB::transaction(function () {
                $party_date = $this->party_date != "" ? $this->party_date : null;
                $location = $this->location != "" ? $this->location : null;
                $party_type = $this->party_type != "" ? $this->party_type : null;
                $start_time = $this->start_time != "" ? $this->start_time : null;
                $end_time = $this->end_time != "" ? $this->end_time : null;
                $guest_amount = $this->guest_amount != "" ? $this->guest_amount : null;

                $quote = Quote::with('quote_statuses')->updateOrCreate(
                    ['id' => $this->modelId],
                    [
                        'title' => $this->title,
                        'description' => trim($this->description, " \n\r\t\v\0"),
                        'expiration_date' => $this->expiration_date,
                        'quote_text' => trim($this->quote_text, " \n\r\t\v\0"),
                        'customer_id' => $this->customer,
                        'prices_exclude_tax' => $this->prices_exclude_tax,
                        'show_product_group_images' => $this->show_product_group_images,
                        'show_packages' => $this->show_packages,
                        'show_amount_and_total' => $this->show_amount_and_total,
                        'party_date' => $party_date,
                        'location' => $location,
                        'party_type' => $party_type,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'guest_amount' => $guest_amount,
                    ]
                );

                if ($this->new_package_image) {
                    $quote->addMedia($this->new_package_image->path())->toMediaCollection('packages');
                } elseif ($this->package_image == null) {
                    $quote->clearMediaCollection('packages');
                }

                $this->previous_quote_status = $quote->quote_statuses->first();

                if ($this->status != (isset($this->previous_quote_status) ? $this->previous_quote_status->id : 0) OR $this->status_comment != (isset($this->previous_quote_status) ? $this->previous_quote_status->pivot->comment : '')) {
                    $quote->quote_statuses()->attach($this->status, [
                        'comment' => $this->status_comment,
                    ]);
                }

                $this->modelId = $quote->id;

                if (count($this->quote_products_to_be_removed) > 0) {
                    foreach ($this->quote_products_to_be_removed as $index => $quote_product_to_be_removed) {
                        QuoteProduct::destroy($quote_product_to_be_removed);
                    }
                }

                if (count($this->quote_products_to_be_removed) > 0) {
                    foreach ($this->quote_products_to_be_removed as $index => $quote_product_to_be_removed) {
                        SelectedQuoteProduct::destroy($quote_product_to_be_removed);
                    }
                }

                foreach ($this->quoteproducts as $index => $order) {
                    $product_id = isset($this->product_id[$index]) ? $this->product_id[$index] : null;
                    $product_description = isset($this->product_description[$index]) ? $this->product_description[$index] : null;
                    $highlight_text = isset($this->product_highlight_text[$index]) ? ($this->product_highlight_text[$index] != '' ? $this->product_highlight_text[$index] : null) : null;

                    $quote_product = QuoteProduct::updateOrCreate([
                        'id' => $product_id
                    ],
                    [
                        'name' => $this->product_name[$index],
                        'purchase_price_excluding_tax' => $this->product_purchase_price_excluding_tax[$index],
                        'purchase_price_including_tax' => $this->product_purchase_price_including_tax[$index],
                        'price_customer_excluding_tax' => $this->product_price_customer_excluding_tax[$index],
                        'price_customer_including_tax' => $this->product_price_customer_including_tax[$index],
                        'amount' => $this->product_amount[$index],
                        'total_price_customer_excluding_tax' => $this->product_total_excluding_tax[$index],
                        'total_price_customer_including_tax' => $this->product_total_including_tax[$index],
                        'tax_percentage' => $this->product_tax_percentage[$index],
                        'description' => trim($product_description, " \n\r\t\v\0"),
                        'quote_id' => $quote->id,
                        'show_product_images' => $this->product_show_images[$index],
                        'order' => $order,
                        'highlight_text' => $highlight_text,
                        'discount_price_customer_excluding_tax' => $this->discount_price_customer_excluding_tax[$index],
                        'discount_price_customer_including_tax' => $this->discount_price_customer_including_tax[$index],
                        'total_discount_price_customer_excluding_tax' => $this->total_discount_price_customer_excluding_tax[$index],
                        'total_discount_price_customer_including_tax' => $this->total_discount_price_customer_including_tax[$index],
                        'use_discount_prices' => $this->use_discount_prices[$index],
                    ]);

                    if (count($this->images_to_be_removed[$index]) > 0) {
                        foreach ($this->images_to_be_removed[$index] as $index_image => $image_to_be_removed) {
                            Media::where('id', $image_to_be_removed)->delete();
                        }
                    }
                    
                    foreach($this->images[$index] as $index_image => $image) {
                        $image_order = isset($this->image_order[$index][$index_image]) ? $this->image_order[$index][$index_image] : max($this->image_order[$index]) + 1;
        
                        if (isset($this->image_copy[$index][$index_image])) {
                            Media::where('id',$this->image_copy[$index][$index_image])->first()->copy($quote_product,'product_images')->setCustomProperty('order_column', $image_order);
                            continue;
                        }

                        if (isset($this->image_id[$index][$index_image])) {
                            Media::where('id',$this->image_id[$index][$index_image])
                            ->update([
                                'order_column' => $image_order,
                            ]);
                        } else {
                            $quote_product->addMedia($this->image_path[$index][$index_image])->toMediaCollection('product_images')->setCustomProperty('order_column', $image_order);
                        }
                    }
                }

                $selected_product_groups_array = [];
                foreach($this->selected_product_groups as $id => $value) {
                    if (count($this->product_groups->where('id',$id)) > 0 AND $value === true) {
                        array_push($selected_product_groups_array, $id);
                    }
                }
                $quote->product_groups()->sync($selected_product_groups_array);

                if ($this->flush_cache === true) {
                    Cache::flush('quote' . $this->modelId);
                }
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De offerte is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('quote.index'));
    }

    public function ProductSelector($index)
    {
        $this->dispatch('getIndexValue', $index)->to('order.product-select');
        $this->dispatch('openProductSelectorModal');
    }

    public function render()
    {       
        return view('content.quote.livewire.form');
    }
}
