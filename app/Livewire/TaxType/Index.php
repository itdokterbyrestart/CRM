<?php

namespace App\Livewire\TaxType;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    TaxType,
};
use Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginationItemsAmount = 30;

    public $selectedItem, $edit;
    public $search = '';
    public $sortColumn = 'percentage', $sortDirection = 'DESC';

    public function mount()
    {
        if (!Auth::user()->can('manage taxtypes')) {
            return abort(403);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%'.$this->search.'%';

        $tax_types = TaxType::query()
            ->orWhere('id','like',$search)
            ->orWhere('name','like',$search)
            ->orWhere('percentage','like',$search);
            if (!strtotime($this->search)) {
                $tax_types
                ->orWhereDay('created_at',$this->search)
                ->orWhereMonth('created_at', $this->search)
                ->orWhereYear('created_at', $this->search)
                ->orWhereDay('updated_at',$this->search)
                ->orWhereMonth('updated_at', $this->search)
                ->orWhereYear('updated_at', $this->search);
            } else {
                $date_search = date('Y-m-d',strtotime($this->search));
                $tax_types
                ->orWhereDate('created_at','=',$date_search)
                ->orWhereDate('updated_at','=',$date_search);
            }
        $tax_types = $tax_types
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->paginationItemsAmount);

        return view('content.taxtype.livewire.index', compact('tax_types'));
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
            'title' => 'Weet je zeker dat je dit belastingtype wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }
    
    #[On('delete')]
    public function delete($taxtype_id)
    {
        try{
            DB::transaction(function () use ($taxtype_id) {
                TaxType::destroy($taxtype_id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! Het belastingtype is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Het belastingtype is succesvol verwijderd!',
            'text' => '',
        ]);
    }

    public function updateItem($itemID)
    {
        $this->selectedItem = $itemID;
        $this->edit = 1;
        $this->dispatch('getModelId', $this->selectedItem, $this->edit)->to('tax-type.form');
        $this->dispatch('openModal');
    }

    public function showItem($itemID)
    {
        $this->selectedItem = $itemID;
        $this->edit = 0;
        $this->dispatch('getModelId', $this->selectedItem, $this->edit)->to('tax-type.form');
        $this->dispatch('openModal');
    }
}
