<?php

namespace App\Livewire\HourType;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    HourType,
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

        $hour_types = HourType::query()
            ->orWhere('id','like',$search)
            ->orWhere('name','like',$search)
            ->orWhere('price_customer_excluding_tax','like',$search)
            ->orWhere('price_customer_including_tax','like',$search);
            if (!strtotime($this->search)) {
                $hour_types
                ->orWhereDay('created_at',$this->search)
                ->orWhereMonth('created_at', $this->search)
                ->orWhereYear('created_at', $this->search)
                ->orWhereDay('updated_at',$this->search)
                ->orWhereMonth('updated_at', $this->search)
                ->orWhereYear('updated_at', $this->search);
            } else {
                $date_search = date('Y-m-d',strtotime($this->search));
                $hour_types
                ->orWhereDate('created_at','=',$date_search)
                ->orWhereDate('updated_at','=',$date_search);
            }
        $hour_types = $hour_types
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->paginationItemsAmount);

        return view('content.hourtype.livewire.index', compact('hour_types'));
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
            'title' => 'Weet je zeker dat je dit uurtype wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }
    
    #[On('delete')]
    public function delete($hourtype_id)
    {
        try{
            DB::transaction(function () use ($hourtype_id) {
                HourType::destroy($hourtype_id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! Het uurtype is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Het uurtype is succesvol verwijderd!',
            'text' => '',
        ]);
    }
}
