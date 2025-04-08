<?php

namespace App\Livewire\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    User,
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
    public $sortColumn = 'name', $sortDirection = 'ASC';

    public function mount()
    {
        if (!Auth::user()->can('manage users')) {
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

        $users = User::query();
            $users->where(function ($q) use ($search) {
                $q->Where('id','like',$search)
                ->orWhere('name','like',$search)
                ->orWhere('email','like',$search);
            });
            if (!strtotime($this->search)) {
                $users
                ->orWhereDay('created_at',$this->search)
                ->orWhereMonth('created_at', $this->search)
                ->orWhereYear('created_at', $this->search)
                ->orWhereDay('updated_at',$this->search)
                ->orWhereMonth('updated_at', $this->search)
                ->orWhereYear('updated_at', $this->search);
            } else {
                $date_search = date('Y-m-d',strtotime($this->search));
                $users
                ->orWhereDate('created_at','=',$date_search)
                ->orWhereDate('updated_at','=',$date_search);
            }
            
        $users = $users
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->paginationItemsAmount);

        return view('content.user.livewire.index', compact('users'));
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
        if (!Auth::user()->can('delete user')) {
            abort(403);
        }

        if ($id == Auth::user()->id) {
            return $this->dispatch('swal:error', [
                'type' => 'error',
                'title' => 'Je kunt jezelf niet verwijderen',
                'text' => '',
            ]);
        }
        
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => 'Weet je zeker dat je deze gebruiker wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }
    
    #[On('delete')]
    public function delete($user_id)
    {
        if (!Auth::user()->can('delete user')) {
            abort(403);
        }
        
        try{
            DB::transaction(function () use ($user_id) {
                User::destroy($user_id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De gebruiker is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De gebruiker is succesvol verwijderd!',
            'text' => '',
        ]);
    }
}
