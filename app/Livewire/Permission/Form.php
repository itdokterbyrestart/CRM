<?php

namespace App\Livewire\Permission;

use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Permission;
use Auth;

class Form extends Component
{
    public $name, $contextual_class, $updated_at, $created_at, $modelId;
    public $edit = 1;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|unique:permissions,name,' . $this->modelId,
        ];
    }

    protected $validationAttributes = [
        'name' => 'naam',
    ];

    public function mount($model, $edit)
    {
        if (!Auth::user()->can('manage permissions')) {
            return abort(403);
        }
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit === 0) {
                $this->updated_at= $model->updated_at->format('d-m-Y | H:i');
                $this->created_at = $model->created_at->format('d-m-Y | H:i');
            }        
            $this->name = $model->name;
        }
        
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $this->validate();

        $this->name = strtolower($this->name);

        try {
            Permission::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                ]
            );
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De permissie is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('permission.index'));
    }

    public function render()
    {
        return view('content.permission.livewire.form');
    }
}
