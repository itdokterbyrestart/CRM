<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Customer,
    Service,
    CustomerService,
};
use Illuminate\Support\Carbon;

class Form extends Component
{ 
    public $name, $company, $email, $email2, $email3, $phone, $phone2, $phone3, $street, $number, $postal_code, $place_name, $discount, $comment, $generated = false, $updated_at, $created_at, $modelId;
    public $edit = 1;

    public $services, $customerservices = [];
    public $service_id, $customer_service_id, $service_month, $service_description;
    public $service_updated_at, $service_created_at;
    public $customer_services_to_be_removed = [];

    public $order_products_and_hours = [];
    
    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|unique:customers,name,' . $this->modelId,
            'company' => 'nullable|string|max:255',
            'discount' => 'nullable|integer|between:0,100',
            'email' => 'nullable|email|max:255',
            'email2' => 'nullable|email|max:255',
            'email3' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'phone3' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:7',
            'place_name' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
            'generated' => 'required|boolean',

            'service_id' => 'array|nullable',
            'service_id.*' => 'required|integer|exists:services,id',
            'service_month' => 'array|nullable',
            'service_month.*' => 'required|integer|between:1,12',
            'service_description' => 'array|nullable',
            'service_description.*' => 'sometimes|nullable|string',
        ];
    }

    protected $validationAttributes = [
        'name' => 'naam',
        'company' => 'bedrijf',
        'discount' => 'korting',
        'email' => 'email',
        'email2' => 'email 2',
        'email3' => 'email 3',
        'phone' => 'telefoon',
        'phone2' => 'telefoon 2',
        'phone3' => 'telefoon 3',
        'street' => 'straat',
        'number' => 'nummer',
        'postal_code' => 'postcode',
        'place_name' => 'plaatsnaam',
        'comment' => 'commentaar',
        'generated' => 'gegenereerd',
        'service_id' => 'services',
        'service_id.*' => 'service',
        'service_month' => 'maanden',
        'service_month.*' => 'maand',
        'service_description' => 'beschrijvingen',
        'service_description.*' => 'beschrijving',
    ];

    public function mount($model, $edit)
    {
        $this->services = Service::orderBy('name')->get();
        $this->edit = $edit;
        if ($model) {
            $this->modelId = $model->id;
            if ($edit == 0) {
                $this->updated_at= $model->updated_at->format('d-m-Y | H:i');
                $this->created_at = $model->created_at->format('d-m-Y | H:i');
            }
            $this->name = $model->name;
            $this->company = $model->company;
            $this->discount = $model->discount;
            $this->email = $model->email;
            $this->email2 = $model->email_2;
            $this->email3 = $model->email_3;
            $this->phone = $model->phone;
            $this->phone2 = $model->phone_2;
            $this->phone3 = $model->phone_3;
            $this->street = $model->street;
            $this->number = $model->number;
            $this->postal_code = $model->postal_code;
            $this->place_name = $model->place_name;
            $this->comment = $model->comment;
            $this->generated = $model->generated;
            foreach (CustomerService::where('customer_id',$model->id)->get() as $index => $customer_service) {
                $this->customerservices[] = '';
                $this->customer_service_id[$index] = $customer_service->id;
                $this->service_id[$index] = $customer_service->service_id;
                $this->service_month[$index] = $customer_service->month;
                $this->service_description[$index] = $customer_service->description;
                if ($edit == 0) {
                    $this->service_updated_at[$index] = $customer_service->updated_at->format('d-m-Y | H:i');
                    $this->service_created_at[$index] = $customer_service->created_at->format('d-m-Y | H:i');
                }
            }
            foreach ($model->orders as $order) {
                foreach ($order->order_products as $order_product) {
                    $this->order_products_and_hours[] = [
                        'type' => 'Product',
                        'name' => $order_product->name,
                        'amount' => $order_product->amount,
                        'total_price_customer_including_tax' => $order_product->total_price_customer_including_tax,
                        'date' => Carbon::parse($order_product->created_at),
                        'description' => $order_product->description,
                    ];
                }
                foreach ($order->order_hours as $order_hour) {
                    $this->order_products_and_hours[] = [
                        'type' => 'Uur',
                        'name' => $order_hour->name,
                        'amount' => $order_hour->amount,
                        'total_price_customer_including_tax' => $order_hour->price_customer_including_tax,
                        'date' => Carbon::parse($order_hour->date),
                        'description' => $order_hour->description,
                    ];
                }
            }
            usort($this->order_products_and_hours, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }
        
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

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

    public function store()
    {
        $this->validate();

        try {
            $customer = Customer::updateOrCreate(
                ['id' => $this->modelId],
                [
                    'name' => $this->name,
                    'company' => $this->company,
                    'discount' => $this->discount ?? 0,
                    'email' => $this->email,
                    'email_2' => $this->email2,
                    'email_3' => $this->email3,
                    'phone' => $this->phone,
                    'phone_2' => $this->phone2,
                    'phone_3' => $this->phone3,
                    'street' => $this->street,
                    'number' => $this->number,
                    'postal_code' => $this->postal_code,
                    'place_name' => $this->place_name,
                    'comment' => $this->comment,
                    'generated' => $this->generated,
                ]
            );

            $this->modelId = $customer->id;

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
                    'customer_id' => $this->modelId,
                ]);
            }

        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De klant is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('customer.index'));
    }

    public function render()
    {
        return view('content.customer.livewire.form');
    }
}
