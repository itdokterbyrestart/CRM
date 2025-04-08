<?php

namespace App\Livewire\Order;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Product,
};


class ProductSelect extends Component
{
    // General
    public $products, $product_id = 0, $index;
    
    public function mount()
    {
        $this->products = Product::orderBy('name')->get();
    }

    #[On('refreshForm')]
    public function refreshForm()
    {
        $this->products = Product::orderBy('name')->get();
    }

    #[On('productSelect')]
    public function productSelect($id)
    {
        $this->product_id = $id;
    }

    protected function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
        ];
    }

    protected $validationAttributes = [
        'product_id' => 'product',
    ];

    #[On('getIndexValue')]
    public function getIndexValue($index) {
        $this->index = $index;
    }

    public function select_product()
    {
        $this->validate();

        $this->dispatch('closeProductSelectorModal');
        $this->dispatch('productSelected', $this->product_id, $this->index)->to('order.form');
        $this->dispatch('productSelected', $this->product_id, $this->index)->to('quote.form');
        $this->cleanVars();
    }

    private function cleanVars()
    {
        $this->product_id = 0;
        $this->index = null;
    }

    #[On('forcedCloseModal')]
    public function forcedCloseModal()
    {
        $this->cleanVars();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openProductModal()
    {
        $this->dispatch('openProductModal');
    }

    public function render()
    {
        return view('content.order.livewire.product_selector');
    }
}
