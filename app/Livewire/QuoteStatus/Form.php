<?php

namespace App\Livewire\QuoteStatus;

use Livewire\Component;
use App\Models\{
    QuoteStatus,
};
use Auth;

class Form extends Component
{
    public $name, $contextual_class = '', $updated_at, $created_at, $modelId, $contextual_classes_array = [];
    public $edit = 1;

    public function mount($model, $edit)
    {
        if (!Auth::user()->can('manage quotestatuses')) {
            return abort(403);
        }
        $this->contextual_classes_array = [
            'primary',
            'secondary',
            'success',
            'danger',
            'warning',
            'info',
            'light',
            'dark',
            'body',
            'muted',
            'white',
            'black-50',
            'white-50',
        ];
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit === 0) {
                $this->updated_at= $model->updated_at->format('d-m-Y | H:i');
                $this->created_at = $model->created_at->format('d-m-Y | H:i');
            }        
            $this->name = $model->name;
            $this->contextual_class = $model->contextual_class;
        }
        
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|unique:quote_statuses,name,' . $this->modelId,
            'contextual_class' => 'required|string|min:2|max:255|in:' . implode(',', $this->contextual_classes_array),
        ];
    }
    
    protected $validationAttributes = [
        'name' => 'naam',
        'contextual_class' => 'contextual_class',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $this->validate();

        if ($this->modelId) {
            $order = QuoteStatus::find($this->modelId)->order;
        } else {
            $order = QuoteStatus::max('order') + 1;
        }
        
        try {
            QuoteStatus::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                    'contextual_class' => strtolower($this->contextual_class),
                    'order' => $order,
                ]
            );
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De status is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('quotestatus.index'));
    }

    public function render()
    {
        return view('content.quotestatus.livewire.form');
    }
}
