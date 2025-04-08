<?php

namespace App\Livewire\ProductGroup;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    ProductGroup,
    Product,
};
use Auth;

class Form extends Component
{
    public $name, $description, $updated_at, $created_at, $modelId;
    public $edit = 1;
    public $deal = 0;
    public $description_before_products = false;
    public $products, $selected_products = [], $selected_products_order = [];
    public $description_count = 3;

    public function mount($model, $edit)
    {
        if (!Auth::user()->can('manage productgroups')) {
            return abort(403);
        }
        $this->products = Product::orderBy('name')->get();
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit === 0) {
                $this->updated_at= $model->updated_at->format('d-m-Y | H:i');
                $this->created_at = $model->created_at->format('d-m-Y | H:i');
            }
            $this->name = $model->name;
            $this->description = $model->description;
            $this->description_before_products = ($model->description_before_products == 1 ? true : false);
            $this->description_count = count(preg_split("/\n|\r\n/", $this->description)) > 3 ? count(preg_split("/\n|\r\n/", $this->description)) : 3;
            foreach ($model->products as $product) {
                $this->selected_products[$product->id] = true;
                $this->selected_products_order[$product->id] = $product->pivot->order;
            }
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|unique:product_groups,name,' . $this->modelId,
            'description' => 'nullable|string',
            'selected_products' => 'array',
            'selected_products_order' => 'array',
            'selected_products_order.*' => 'integer|nullable|distinct'
        ];
    }

    protected $validationAttributes = [
        'name' => 'naam',
        'description' => 'beschrijving',
        'selected_products' => 'geselecteerde producten',
        'selected_products_order' => 'volgorde',
        'selected_products_order.*' => 'volgorde',
    ];

    public function updatingSelectedProducts($value, $id)
    {
        if ($value == false) {
            unset($this->selected_products[$id],$this->selected_products_order[$id]);
        } else {
            $this->selected_products_order[$id] = count($this->selected_products_order) + 1;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $this->validate();

        if ($this->modelId) {
            $order = ProductGroup::find($this->modelId)->order;
        } else {
            $order = ProductGroup::max('order') + 1;
        }
        
        try {
            $product_group = ProductGroup::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                    'description' => trim($this->description, " \n\r\t\v\0"),
                    'order' => $order,
                    'description_before_products' => $this->description_before_products,
                ]
            );

            $this->modelId = $product_group;

            $selected_products_array = [];
            foreach($this->selected_products as $id => $value) {
                if (count($this->products->where('id',$id)) > 0 AND $value === true) {
                    $selected_products_array[$id] = [
                        'order' => $this->selected_products_order[$id],
                    ];
                }
            }

            $product_group->products()->sync($selected_products_array);
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De product groep is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('productgroup.index'));
    }

    public function render()
    {
        return view('content.productgroup.livewire.form');
    }
}
