<?php

namespace App\Livewire\Service\Users;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    CustomerService,
    Service,
    Customer,
};
use Carbon\Carbon;

class Form extends Component
{
    public $customers = [], $customer;
    public $edit = 1;
    public $modelId;

    public $services, $customerservices = [];
    public $service_id = [], $customer_service_id = [], $service_month = [], $service_description = [];
    public $service_updated_at, $service_created_at;
    public $customer_services_to_be_removed = [];

    protected $listeners = [
        'getModelId',
        'forcedCloseModal',
    ];

    protected function rules()
    {
        return [
            'customer' => 'required|integer|exists:customers,id',
            'service_id' => 'array',
            'service_id.*' => 'required|integer|exists:services,id',
            'service_month' => 'array',
            'service_month.*' => 'required|integer|between:1,12',
            'service_description' => 'array',
            'service_description.*' => 'sometimes|nullable|string',
        ];
    }

    public function mount()
    {
        $this->services = Service::orderBy('name')->get();
        $this->customers = Customer::orderBy('name','ASC')->get();
    }

    protected $validationAttributes = [
        'customer' => 'klant',
        'service_id' => 'services',
        'service_id.*' => 'service',
        'service_month' => 'maanden',
        'service_month.*' => 'maand',
        'service_description' => 'beschrijvingen',
        'service_description.*' => 'beschrijving',
    ];

    public function removeService($index)
    {
        if (isset($this->customer_service_id[$index])) {
            $this->customer_services_to_be_removed[] = $this->customer_service_id[$index];
        }
        unset($this->customerservices[$index], $this->customer_service_id[$index], $this->service_id[$index], $this->service_month[$index], $this->service_description[$index]);
    }

    public function addService()
    {
        $this->customerservices[] = '';
        $this->customer_service_id[array_key_last($this->customerservices)] = '';
        $this->service_id[array_key_last($this->customerservices)] = '';
        $this->service_month[array_key_last($this->customerservices)] = '';
        $this->service_description[array_key_last($this->customerservices)] = '';
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedCustomer($value)
    {
        $this->customer = null;

        $this->customerservices = [];
        $this->customer_service_id = [];
        $this->service_id = [];
        $this->service_month = [];
        $this->service_description = [];
        $this->service_updated_at = [];
        $this->service_created_at = [];
        $this->customer_services_to_be_removed = [];

        $this->getModelId($value, $this->edit);
    }

    #[On('getModelId')]
    public function getModelId($modelId, $edit)
    {
        $this->edit = $edit;
        $this->modelId = $modelId;
        $model = Customer::findOrFail($modelId);
        $this->customer = $model->id;
        foreach (CustomerService::where('customer_id',$model->id)->get() as $index => $customer_service) {
            $this->customerservices[$index] = '';
            $this->customer_service_id[$index] = $customer_service->id;
            $this->service_id[$index] = $customer_service->service_id;
            $this->service_month[$index] = $customer_service->month;
            $this->service_description[$index] = $customer_service->description;
            if ($edit === 0) {
                $this->service_updated_at[$index] = $customer_service->updated_at->format('d-m-Y | H:i');
                $this->service_created_at[$index] = $customer_service->created_at->format('d-m-Y | H:i');
            }
        }
    }

    public function store()
    {
        $this->validate();

        try {
            if (count($this->customer_services_to_be_removed) > 0) {
                foreach ($this->customer_services_to_be_removed as $index => $customer_service_to_be_removed) {
                    CustomerService::destroy($customer_service_to_be_removed);
                }
            }

            foreach ($this->customerservices as $index => $customerservice) {

                $customer_service_id = isset($this->customer_service_id[$index]) ? $this->customer_service_id[$index] : null;
                $service_description = isset($this->service_description[$index]) ? $this->service_description[$index] : null;

                CustomerService::updateOrCreate([
                    'id' => $customer_service_id
                ],
                [
                    'service_id' => $this->service_id[$index],
                    'month' => $this->service_month[$index],
                    'description' => trim($service_description, " \n\r\t\v\0"),
                    'customer_id' => $this->customer,
                ]);
            }



        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De services zijn niet aangepast.',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('closeModal');
        $this->dispatch('refreshParent')->to('service.users.index');
        $this->cleanVars();

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De services zijn succesvol aangepast!',
            'text' => '',
        ]);
    }

    private function cleanVars()
    {
        $this->modelId = null;
        $this->customer = null;

        $this->customerservices = [];
        $this->customer_service_id = [];
        $this->service_id = [];
        $this->service_month = [];
        $this->service_description = [];
        $this->service_updated_at = [];
        $this->service_created_at = [];
        $this->customer_services_to_be_removed = [];
        $this->edit = 1;
    }

    public function forcedCloseModal()
    {
        $this->cleanVars();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('content.service.users.livewire.form');
    }
}
