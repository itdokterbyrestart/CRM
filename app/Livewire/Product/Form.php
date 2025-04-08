<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Product,
    ProductGroup,
    TaxType,
    Media,
};
use Livewire\WithFileUploads;


class Form extends Component
{
    use WithFileUploads;
    
    public $name, $description, $profit = 0.00, $purchase_price_excluding_tax = 0.00, $purchase_price_including_tax = 0.00, $price_customer_excluding_tax = 0.00, $price_customer_including_tax = 0.00, $tax_percentage = 21, $link, $updated_at, $created_at, $modelId;
    public $services = [];
    public $edit = 1;
    public $product_groups, $selected_product_groups = [];
    public $tax_types;
    public $description_count = 3;
    // Discount prices
    public $use_discount_price = false, $discount_price_customer_including_tax = 0.00, $discount_price_customer_excluding_tax = 0.00;

    // Images
    public $images = [], $new_images = [], $image_id, $image_link, $image_path, $image_order = [], $images_to_be_removed = [];

    public function mount($model, $edit)
    {
        $this->product_groups = ProductGroup::orderBy('order')->orderBy('name')->get();
        $this->tax_types = TaxType::orderBy('default','DESC')->orderBy('percentage','DESC')->get();
        $this->tax_percentage = $this->tax_types->where('default',1)->first()->percentage ?? 21;
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit === 0) {
                $this->updated_at= $model->updated_at->format('d-m-Y | H:i');
                $this->created_at = $model->created_at->format('d-m-Y | H:i');
            }
            $this->name = $model->name;
            $this->purchase_price_excluding_tax = $model->purchase_price_excluding_tax;
            $this->purchase_price_including_tax = $model->purchase_price_including_tax;
            $this->price_customer_excluding_tax = $model->price_customer_excluding_tax;
            $this->price_customer_including_tax = $model->price_customer_including_tax;
            $this->profit = $model->profit;
            $this->link = $model->link;
            $this->description = $model->description;
            $this->description_count = count(preg_split("/\n|\r\n/", $this->description)) > 3 ? count(preg_split("/\n|\r\n/", $this->description)) : 3;
            $this->tax_percentage = $model->tax_percentage;
            $this->use_discount_price = $model->use_discount_price;
            $this->discount_price_customer_excluding_tax = $model->discount_price_customer_excluding_tax;
            $this->discount_price_customer_including_tax = $model->discount_price_customer_including_tax;
            foreach ($model->product_groups as $product_group) {
                $this->selected_product_groups[$product_group->id] = true;
            }
            $this->services = $model->services;
            foreach ($model->getMedia('product_images') as $index => $image) {
                $this->images[] = '';
                $this->image_id[$index] = $image->id;
                $this->image_order[$index] = $image->order_column;
                $this->image_link[$index] = $image->getUrl();
            }
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|unique:products,name,' . $this->modelId,
            'purchase_price_excluding_tax' => 'required|numeric|between:-99999.99,99999.99',
            'purchase_price_including_tax' => 'required|numeric|between:-99999.99,99999.99',
            'price_customer_excluding_tax' => 'required|numeric|between:-99999.99,99999.99',
            'price_customer_including_tax' => 'required|numeric|between:-99999.99,99999.99',
            'link' => 'nullable|string|url|max:255',
            'description' => 'nullable|string',
            'tax_percentage' => 'required|integer|between:0,100',
            'selected_product_groups' => 'array',
            'new_images' => 'array|max:12',
            'new_images.*' => 'nullable|image|mimes:png,jpg,jpeg|max:10240|dimensions:min_width=50,min_height=50',
            'images' => 'array',
            'use_discount_price' => 'required|boolean',
            'discount_price_customer_including_tax' => 'required|numeric|between:-99999.99,99999.99',
            'discount_price_customer_excluding_tax' => 'required|numeric|between:-99999.99,99999.99',
        ];
    }

    protected $validationAttributes = [
        'name' => 'naam',
        'purchase_price_excluding_tax' => 'inkoopprijs',
        'purchase_price_including_tax' => 'inkoopprijs',
        'price_customer_excluding_tax' => 'klantprijs excl. BTW',
        'price_customer_including_tax' => 'klantprijs incl. BTW',
        'link' => 'link',
        'description' => 'beschrijving',
        'tax_percentage' => 'belasting percentage',
        'selected_product_groups' => 'geselecteerde product groepen',
        'new_images' => 'nieuwe afbeeldingen',
        'new_images.*' => 'afbeelding',
        'images' => 'afbeeldingen',
        'use_discount_price' => 'gebruik kortingsprijs',
        'discount_price_customer_including_tax' => 'kortingsprijs inclusief BTW',
        'discount_price_customer_excluding_tax' => 'kortingsprijs exclusief BTW',
    ];

    public function removeImage($index)
    {
        if (isset($this->image_id[$index])) {
            $this->images_to_be_removed[] = $this->image_id[$index];
        }
        unset($this->images[$index], $this->image_id[$index], $this->image_link[$index], $this->image_order[$index], $this->image_path[$index]);
    }

    public function updatedNewImages($images)
    {
        foreach ($images as $image) {
            $this->images[] = '';
            $this->image_order[array_key_last($this->images)] = count($this->image_order) + 1;
            $this->image_link[array_key_last($this->images)] = $image->temporaryUrl();
            $this->image_path[array_key_last($this->images)] = $image->path();
        }
    }

    public function orderUpdated($order)
    {
        foreach ($order as $item) {
            $this->image_order[$item['value']] = $item['order'];
        }
        asort($this->image_order);
    }

    public function updatingSelectedProductGroups($value, $id)
    {
        if ($value === false) {
            unset($this->selected_product_groups[$id]);
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedPurchasePriceExcludingTax()
    {
        $this->calculatePurchasePriceIncludingTax();
        $this->calculatePriceCustomerExcludingTax(2);
        $this->calculatePriceCustomerIncludingTax();
        $this->calculateProfit();
    }

    public function updatedPurchasePriceIncludingTax()
    {
        $this->calculatePurchasePriceExcludingTax();
        $this->calculatePriceCustomerExcludingTax(2);
        $this->calculatePriceCustomerIncludingTax();
        $this->calculateProfit();
    }

    public function updatedPriceCustomerExcludingTax()
    {
        $this->calculateProfit();
        $this->calculatePriceCustomerIncludingTax();
    } 

    public function updatedPriceCustomerIncludingTax()
    {
        $this->calculatePriceCustomerExcludingTax(0);
        $this->calculateProfit();
    }

    public function updatedTaxPercentage()
    {
        $this->calculatePriceCustomerIncludingTax();
    }

    public function updatedProfit()
    {
        $this->calculatePriceCustomerExcludingTax(1);
        $this->calculatePriceCustomerIncludingTax();
    }

    public function updatedDiscountPriceCustomerExcludingTax()
    {
        $this->calculateProfit();
        $this->calculateDiscountPriceCustomerIncludingTax();
    } 

    public function updatedDiscountPriceCustomerIncludingTax()
    {
        $this->calculateDiscountPriceCustomerExcludingTax();
        $this->calculateProfit();
    }

    public function UpdatedUseDiscountPrice($value)
    {
        if ($value == false) {
            if ($this->discount_price_customer_excluding_tax == null OR $this->discount_price_customer_excluding_tax == '') {
                $this->discount_price_customer_excluding_tax = 0.00;
            }
            if ($this->discount_price_customer_including_tax == null OR $this->discount_price_customer_including_tax == '') {
                $this->discount_price_customer_including_tax = 0.00;
            }
        }
        $this->calculateProfit();
    }

    private function calculateProfit()
    {
        if ($this->use_discount_price == false) {
            if (is_numeric($this->price_customer_excluding_tax) AND is_numeric($this->purchase_price_excluding_tax)) {
                $this->profit = number_format(($this->price_customer_excluding_tax - $this->purchase_price_excluding_tax), 2, '.', '');
            }
        } elseif ($this->use_discount_price == true) {
            if (is_numeric($this->discount_price_customer_excluding_tax) AND is_numeric($this->purchase_price_excluding_tax)) {
                $this->profit = number_format(($this->discount_price_customer_excluding_tax - $this->purchase_price_excluding_tax), 2, '.', '');
            }
        }
    }

    private function calculatePriceCustomerIncludingTax()
    {
        if(is_numeric($this->price_customer_excluding_tax) AND is_numeric($this->tax_percentage)) {
            $this->price_customer_including_tax = number_format(($this->price_customer_excluding_tax * (1 + ($this->tax_percentage / 100))), 2, '.', '');
        }
    }

    private function calculatePriceCustomerExcludingTax($type)
    {
        if ($type === 0) {
            if(is_numeric($this->price_customer_including_tax) AND is_numeric($this->tax_percentage)) {
                $this->price_customer_excluding_tax = number_format(($this->price_customer_including_tax / (1 + ($this->tax_percentage / 100))), 2, '.', '');
            }
        } elseif ($type === 1) {
            if(is_numeric($this->profit) AND is_numeric($this->purchase_price_excluding_tax)) {
                $this->price_customer_excluding_tax = number_format(($this->purchase_price_excluding_tax + $this->profit), 2, '.', '');
            }
        } elseif ($type === 2) {
            if(!is_numeric($this->price_customer_excluding_tax) AND is_numeric($this->purchase_price_excluding_tax)) {
                $this->price_customer_excluding_tax = number_format(($this->purchase_price_excluding_tax * 1.1), 2, '.', '');
            }
        }
    }

    private function calculateDiscountPriceCustomerIncludingTax()
    {
        if(is_numeric($this->discount_price_customer_excluding_tax) AND is_numeric($this->tax_percentage)) {
            $this->discount_price_customer_including_tax = number_format(($this->discount_price_customer_excluding_tax * (1 + ($this->tax_percentage / 100))), 2, '.', '');
        }
    }

    private function calculateDiscountPriceCustomerExcludingTax()
    {
        if(is_numeric($this->discount_price_customer_including_tax) AND is_numeric($this->tax_percentage)) {
            $this->discount_price_customer_excluding_tax = number_format(($this->discount_price_customer_including_tax / (1 + ($this->tax_percentage / 100))), 2, '.', '');
        }
    }

    private function calculatePurchasePriceIncludingTax()
    {
        if(is_numeric($this->purchase_price_excluding_tax) AND is_numeric($this->tax_percentage)) {
            $this->purchase_price_including_tax = number_format(($this->purchase_price_excluding_tax * (1 + ($this->tax_percentage / 100))), 2, '.', '');
        }
    }

    private function calculatePurchasePriceExcludingTax()
    {
        if(is_numeric($this->purchase_price_including_tax) AND is_numeric($this->tax_percentage)) {
            $this->purchase_price_excluding_tax = number_format(($this->purchase_price_including_tax / (1 + ($this->tax_percentage / 100))), 2, '.', '');
        }
    }
   
    public function store()
    {
        $this->validate();

        try {
            $product = Product::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                    'purchase_price_excluding_tax' => $this->purchase_price_excluding_tax,
                    'purchase_price_including_tax' => $this->purchase_price_including_tax,
                    'price_customer_excluding_tax' => $this->price_customer_excluding_tax,
                    'price_customer_including_tax' => $this->price_customer_including_tax,
                    'profit' => $this->profit,
                    'link' => $this->link,
                    'description' => trim($this->description, " \n\r\t\v\0"),
                    'tax_percentage' => $this->tax_percentage,
                    'use_discount_price' => $this->use_discount_price,
                    'discount_price_customer_including_tax' => $this->discount_price_customer_including_tax,
                    'discount_price_customer_excluding_tax' => $this->discount_price_customer_excluding_tax,
                ]
            );

            $selected_product_groups_array = [];
                foreach($this->selected_product_groups as $id => $value) {
                    if (count($this->product_groups->where('id',$id)) > 0 AND $value === true) {
                        array_push($selected_product_groups_array, $id);
                    }
                }
            $product->product_groups()->sync($selected_product_groups_array);

            if (count($this->images_to_be_removed) > 0) {
                foreach ($this->images_to_be_removed as $index => $image_to_be_removed) {
                    Media::where('id', $image_to_be_removed)->delete();
                }
            }
            
            foreach($this->images as $index => $image) {
                $image_order = isset($this->image_order[$index]) ? $this->image_order[$index] : 0;

                if (isset($this->image_id[$index])) {
                    Media::where('id',$this->image_id[$index])
                        ->update([
                            'order_column' => $image_order,
                        ]);
                } else {
                    $product->addMedia($this->image_path[$index])->toMediaCollection('product_images')->setCustomProperty('order_column', $image_order);
                }
            }

        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! Het product is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('product.index'));
    }

    public function render()
    {
        return view('content.product.livewire.form');
    }
}
