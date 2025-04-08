<?php

namespace App\Livewire\Setting;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Setting,
};
use Auth;

class Form extends Component
{
    public $name, $value, $updated_at, $created_at, $modelId;
    public $edit = 1;

    public function mount($model, $edit)
    {
        if (!Auth::user()->can('manage settings')) {
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
            $this->value = $model->value;
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|unique:settings,name,' . $this->modelId,
        ];
    }

    protected $validationAttributes = [
        'name' => 'naam',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $this->validate();

        $this->name = strtolower($this->name);

        try {
            Setting::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                    'value' => $this->value,
                ]
            );
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De instelling is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('setting.index'));
    }

    public function render()
    {
        return view('content.setting.livewire.form');
    }
}
