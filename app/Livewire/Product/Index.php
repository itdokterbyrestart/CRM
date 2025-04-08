<?php

namespace App\Livewire\Product;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Product,
};
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginationItemsAmount = 30;

    public $selectedItem, $edit;
    public $search = '';
    public $sortColumn = 'name', $sortDirection = 'ASC';

    public $showProductImages = false;
    public $showPurchasePrice = false;
    public $showPriceCustomer = true;
    public $showProfit = true;
    public $showMargin = true;
    public $showTax = false;
    public $showLink = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%'.$this->search.'%';

        $products = Product::query()
            ->orWhere('id','like',$search)
            ->orWhere('name','like',$search)
            ->orWhere('purchase_price_excluding_tax','like',$search)
            ->orWhere('purchase_price_including_tax','like',$search)
            ->orWhere('price_customer_including_tax','like',$search)
            ->orWhere('price_customer_excluding_tax','like',$search)
            ->orWhere('profit','like',$search)
            ->orWhere('link','like',$search);
            if (!strtotime($this->search)) {
                $products
                ->orWhereDay('created_at',$this->search)
                ->orWhereMonth('created_at', $this->search)
                ->orWhereYear('created_at', $this->search)
                ->orWhereDay('updated_at',$this->search)
                ->orWhereMonth('updated_at', $this->search)
                ->orWhereYear('updated_at', $this->search);
            } else {
                $date_search = date('Y-m-d',strtotime($this->search));
                $products
                ->orWhereDate('created_at','=',$date_search)
                ->orWhereDate('updated_at','=',$date_search);
            }
        $products = $products
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->paginationItemsAmount);

        return view('content.product.livewire.index', compact('products'));
    }

    public function sortBy($field)
    {
        if ($this->sortDirection === 'ASC') {
            $this->sortDirection = 'DESC';
        } else {
            $this->sortDirection = 'ASC';
        }

        return $this->sortColumn = $field;
    }

    public function deleteConfirm($id)
    {
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => 'Weet je zeker dat je dit product wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }

    #[On('delete')]
    public function delete($product_id)
    {
        try{
            DB::transaction(function () use ($product_id) {
                Product::destroy($product_id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! Het product is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Het product is succesvol verwijderd!',
            'text' => '',
        ]);
    }

    public function clone($product_id)
    {
        $product = Product::find($product_id)->clone();
        return redirect(route('product.edit', $product));
    }
}
