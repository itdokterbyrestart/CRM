<?php

namespace App\Livewire\Service;

use Livewire\Component;
use App\Models\{
    Service,
    Product,
};

class Form extends Component
{
    public $name, $product_id = '', $updated_at, $created_at, $modelId;
    public $products = [];
    public $edit = 1;

    public function mount($model, $edit)
    {
        $this->products = Product::orderBy('name')->get();
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit === 0) {
                $this->updated_at= $model->updated_at->format('d-m-Y | H:i');
                $this->created_at = $model->created_at->format('d-m-Y | H:i');
            }
            $this->name = $model->name;
            $this->product_id = $model->product->id;
        }
            
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|unique:services,name,' . $this->modelId,
            'product_id' => 'required|exists:products,id',
        ];
    }

    protected $validationAttributes = [
        'name' => 'naam',
        'product_id' => 'product'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $this->validate();

        try {
            Service::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                    'product_id' => $this->product_id,
                ]
            );
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De service is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('service.index'));
    }

    public function render()
    {
        return view('content.service.livewire.form');
    }
}
