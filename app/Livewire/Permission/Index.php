<?php

namespace App\Livewire\Permission;

use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginationItemsAmount = 30;

    public $selectedItem, $edit;
    public $search = '';
    public $sortColumn = 'created_at', $sortDirection = 'ASC';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $search = '%'.$this->search.'%';

        $permissions = Permission::query()
            ->orWhere('id','like',$search)    
            ->orWhere('name','like',$search);
            if (!strtotime($this->search)) {
                $permissions
                ->orWhereDay('created_at',$this->search)
                ->orWhereMonth('created_at', $this->search)
                ->orWhereYear('created_at', $this->search)
                ->orWhereDay('updated_at',$this->search)
                ->orWhereMonth('updated_at', $this->search)
                ->orWhereYear('updated_at', $this->search);
            } else {
                $date_search = date('Y-m-d',strtotime($this->search));
                $permissions
                ->orWhereDate('created_at','=',$date_search)
                ->orWhereDate('updated_at','=',$date_search);
            }
            if ($this->sortColumn) {
                $permissions->orderBy($this->sortColumn, $this->sortDirection);
            }
        $permissions = $permissions->paginate($this->paginationItemsAmount);

        return view('content.permission.livewire.index', compact('permissions'));
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
            'title' => 'Weet je zeker dat je deze rol wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }
    
    #[On('delete')]
    public function delete($permission_id)
    {
        try{
            DB::transaction(function () use ($permission_id) {
                Permission::destroy($permission_id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De permissie is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De permissie is succesvol verwijderd!',
            'text' => '',
        ]);
    }
}
