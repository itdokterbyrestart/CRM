<?php

namespace App\Livewire\HourType;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    HourType,
    TaxType,
};
use Auth;

class Form extends Component
{
    public $name, $price_customer_excluding_tax, $price_customer_including_tax, $tax_percentage, $updated_at, $created_at, $modelId;
    public $edit = 1, $deal = 0;
    public $tax_types;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|unique:hour_types,name,' . $this->modelId,
            'price_customer_excluding_tax' => 'required|numeric|between:-99999.99,99999.99',
            'price_customer_including_tax' => 'required|numeric|between:-99999.99,99999.99',
            'tax_percentage' => 'required|integer|between:0,100',
        ];
    }

    public function mount($model, $edit)
    {
        if (!Auth::user()->can('manage hourtypes')) {
            return abort(403);
        }
        $this->tax_types = TaxType::orderBy('default','DESC')->orderBy('percentage','DESC')->get();
        $this->tax_percentage = $this->tax_types->where('default',1)->first()->percentage ?? 21;
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit === 0) {
                $this->updated_at= $model->updated_at->format('d-m-Y | H:i');
                $this->created_at = $model->created_at->format('d-m-Y | H:i');
            }
            $this->name = $model->name;
            $this->price_customer_excluding_tax = $model->price_customer_excluding_tax;
            $this->price_customer_including_tax = $model->price_customer_including_tax;
            $this->tax_percentage = $model->tax_percentage;
        }
    }

    protected $validationAttributes = [
        'name' => 'naam',
        'price_customer_excluding_tax' => 'klantprijs excl. BTW',
        'price_customer_including_tax' => 'klantprijs incl. BTW',
        'tax_percentage' => 'belasting percentage',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedPriceCustomerExcludingTax()
    {
        $this->calculatePriceCustomerIncludingTax();
    }

    public function updatedTaxPercentage()
    {
        $this->calculatePriceCustomerIncludingTax();
    }

    public function updatedPriceCustomerIncludingTax()
    {
        $this->calculatePriceCustomerExcludingTax();
    }

    private function calculatePriceCustomerIncludingTax()
    {
        if(is_numeric($this->price_customer_excluding_tax) AND is_numeric($this->tax_percentage)) {
            $this->price_customer_including_tax = number_format(($this->price_customer_excluding_tax * (1 + ($this->tax_percentage / 100))), 2, '.', '');
        }
    }

    private function calculatePriceCustomerExcludingTax()
    {
        if(is_numeric($this->price_customer_including_tax) AND is_numeric($this->tax_percentage)) {
            $this->price_customer_excluding_tax = number_format(($this->price_customer_including_tax / (1 + ($this->tax_percentage / 100))), 2, '.', '');
        }
    }

    public function store()
    {
        $this->validate();
        
        try {
            HourType::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                    'price_customer_excluding_tax' => $this->price_customer_excluding_tax,
                    'price_customer_including_tax' => $this->price_customer_including_tax,
                    'tax_percentage' => $this->tax_percentage,
                ]
            );
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! Het uurtype is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('hourtype.index'));
    }

    public function render()
    {
        return view('content.hourtype.livewire.form');
    }
}
