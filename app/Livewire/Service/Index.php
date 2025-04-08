<?php

namespace App\Livewire\Service;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Service,
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%'.$this->search.'%';

        $services = Service::query()
            ->with('product')
            ->orWhere('id','like',$search)
            ->orWhere('name','like',$search)
            ->orWhereHas('product', function ($q) use ($search) {
                $q->where('name','like',$search);
            });
            if (!strtotime($this->search)) {
                $services
                ->orWhereDay('created_at',$this->search)
                ->orWhereMonth('created_at', $this->search)
                ->orWhereYear('created_at', $this->search)
                ->orWhereDay('updated_at',$this->search)
                ->orWhereMonth('updated_at', $this->search)
                ->orWhereYear('updated_at', $this->search);
            } else {
                $date_search = date('Y-m-d',strtotime($this->search));
                $services
                ->orWhereDate('created_at','=',$date_search)
                ->orWhereDate('updated_at','=',$date_search);
            }
        $services = $services
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->paginationItemsAmount);

        return view('content.service.livewire.index', compact('services'));
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
            'title' => 'Weet je zeker dat je deze service wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }

    #[On('delete')]
    public function delete($service_id)
    {
        try{
            DB::transaction(function () use ($service_id) {
                Service::destroy($service_id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De service is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De service is succesvol verwijderd!',
            'text' => '',
        ]);
    }

    public function updateItem($itemID)
    {
        $this->selectedItem = $itemID;
        $this->edit = 1;
        $this->dispatch('getModelId', $this->selectedItem, $this->edit)->to('service.form');
        $this->dispatch('openModal');
    }

    public function showItem($itemID)
    {
        $this->selectedItem = $itemID;
        $this->edit = 0;
        $this->dispatch('getModelId', $this->selectedItem, $this->edit)->to('service.form');
        $this->dispatch('openModal');
    }
}
