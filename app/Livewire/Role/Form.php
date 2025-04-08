<?php

namespace App\Livewire\Role;

use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\{
    Role,
    Permission,
};
use Auth;


class Form extends Component
{
    public $name, $contextual_class, $updated_at, $created_at, $modelId, $permissions, $selected_permissions = [];
    public $edit = 1;

    public function mount($model, $edit)
    {
        if (!Auth::user()->can('manage roles')) {
            return abort(403);
        }
        $this->permissions = Permission::orderBy('name')->get();
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit === 0) {
                $this->updated_at= $model->updated_at->format('d-m-Y | H:i');
                $this->created_at = $model->created_at->format('d-m-Y | H:i');
            }        
            $this->name = $model->name;
            foreach ($model->permissions as $permission) {
                $this->selected_permissions[$permission->id] = true;
            }
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|unique:roles,name,' . $this->modelId,
            'selected_permissions' => 'array',
        ];
    }

    protected $validationAttributes = [
        'name' => 'naam',
        'selected_permissions' => 'geselecteerde permissies',
        'permissions' => 'permissies',
        'permissions.*' => 'permissie',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatingSelectedPermissions($value, $id)
    {
        if ($value === false) {
            unset($this->selected_permissions[$id]);
        }
    } 

    public function store()
    {
        $this->validate();

        $selected_permissions_array = [];
        foreach($this->selected_permissions as $id => $value) {
            if (count($this->permissions->where('id',$id)) > 0 AND $value === true) {
                array_push($selected_permissions_array, $id);
            }
        }

        try {
            Role::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                ]
            )->syncPermissions($selected_permissions_array);
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De rol is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('role.index'));
    }

    public function render()
    {
        return view('content.role.livewire.form');
    }
}
