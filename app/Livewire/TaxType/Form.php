<?php

namespace App\Livewire\TaxType;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    TaxType,
};
use Auth;

class Form extends Component
{
    public $name, $percentage, $default = false, $updated_at, $created_at, $modelId;
    public $edit = 1;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|unique:tax_types,name,' . $this->modelId,
            'percentage' => 'required|integer|between:0,100',
            'default' => 'required|boolean',
        ];
    }

    public function mount($model, $edit)
    {
        if (!Auth::user()->can('manage taxtypes')) {
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
            $this->percentage = $model->percentage;
            $this->default = ($model->default == 1 ? true : false);
        }
        
    }

    protected $validationAttributes = [
        'name' => 'naam',
        'percentage' => 'percentage',
        'default' => 'standaard',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $this->validate();
        
        try {
            TaxType::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                    'percentage' => $this->percentage,
                    'default' => $this->default,
                ]
            );
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! Het belastingtype is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('taxtype.index'));
    }

    public function render()
    {
        return view('content.taxtype.livewire.form');
    }
}
