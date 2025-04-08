<?php

namespace App\Livewire\Setting;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Setting,
};
use Illuminate\Support\Facades\DB;

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

        $settings = Setting::query()
            ->orWhere('id','like',$search)    
            ->orWhere('name','like',$search)
            ->orWhere('value','like',$search);
            if (!strtotime($this->search)) {
                $settings
                ->orWhereDay('created_at',$this->search)
                ->orWhereMonth('created_at', $this->search)
                ->orWhereYear('created_at', $this->search)
                ->orWhereDay('updated_at',$this->search)
                ->orWhereMonth('updated_at', $this->search)
                ->orWhereYear('updated_at', $this->search);
            } else {
                $date_search = date('Y-m-d',strtotime($this->search));
                $settings
                ->orWhereDate('created_at','=',$date_search)
                ->orWhereDate('updated_at','=',$date_search);
            }
            if ($this->sortColumn) {
                $settings->orderBy($this->sortColumn, $this->sortDirection);
            }
        $settings = $settings->paginate($this->paginationItemsAmount);

        return view('content.setting.livewire.index', compact('settings'));
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
            'title' => 'Weet je zeker dat je deze instelling wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }
    
    #[On('delete')]
    public function delete($setting_id)
    {
        try{
            DB::transaction(function () use ($setting_id) {
                Setting::destroy($setting_id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De instelling is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De instelling is succesvol verwijderd!',
            'text' => '',
        ]);
    }
}
