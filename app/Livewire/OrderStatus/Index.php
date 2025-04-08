<?php

namespace App\Livewire\OrderStatus;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    OrderStatus,
};
use Illuminate\Support\Facades\DB;
use Auth;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginationItemsAmount = 30;

    public $selectedItem, $edit;
    public $search = '';
    public $sortColumn = 'order', $sortDirection = 'ASC';

    public function mount()
    {
        if (!Auth::user()->can('manage orderstatuses')) {
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

        $orderstatus = OrderStatus::query()
            ->orWhere('id','like',$search)    
            ->orWhere('name','like',$search);
            if (!strtotime($this->search)) {
                $orderstatus
                ->orWhereDay('created_at',$this->search)
                ->orWhereMonth('created_at', $this->search)
                ->orWhereYear('created_at', $this->search)
                ->orWhereDay('updated_at',$this->search)
                ->orWhereMonth('updated_at', $this->search)
                ->orWhereYear('updated_at', $this->search);
            } else {
                $date_search = date('Y-m-d',strtotime($this->search));
                $orderstatus
                ->orWhereDate('created_at','=',$date_search)
                ->orWhereDate('updated_at','=',$date_search);
            }
            if ($this->sortColumn) {
                $orderstatus->orderBy($this->sortColumn, $this->sortDirection);
            }
        $orderstatus = $orderstatus->paginate($this->paginationItemsAmount);

        return view('content.orderstatus.livewire.index', compact('orderstatus'));
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
            'title' => 'Weet je zeker dat je deze status wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }
    
    #[On('delete')]
    public function delete($orderstatus_id)
    {
        try{
            DB::transaction(function () use ($orderstatus_id) {
                OrderStatus::destroy($orderstatus_id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De status is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De status is succesvol verwijderd!',
            'text' => '',
        ]);
    }

    public function updateOrder($list)
    {
        $table = app(OrderStatus::class)->getTable();
        foreach($list as $type)
        {
            DB::table($table)
                ->where('id', $type['value'])
                ->update(['order' => $type['order']]);
        }
    }
}
