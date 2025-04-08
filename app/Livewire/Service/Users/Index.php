<?php

namespace App\Livewire\Service\Users;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    CustomerService,
    Service,
    Setting,
};
use Illuminate\Support\Facades\Mail;
use App\Mail\ApkInvitationMail;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginationItemsAmount = 30;

    public $selectedItem, $edit;
    public $search = '';
    public $sortColumn = 'customer_id', $sortDirection = 'ASC';

    public $selectedServices = [], $services = [];

    public function mount()
    {
        $this->services = Service::orderBy('name')->get();
    }

    protected $listeners = [
        'refreshParent' => '$refresh',
        'delete',
        'sendInvitationMail',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%'.$this->search.'%';

        $models = CustomerService::query()
            ->with('customer','service');
            if(count($this->selectedServices) > 0) {
                $models->whereIn('service_id', array_keys($this->selectedServices));
            }
            if ($search != '%%') {
                $models->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('name','like',$search);
                })
                ->orWhereHas('service', function ($q) use ($search) {
                    $q->where('name','like',$search);
                });
            }
        $models = $models->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->paginationItemsAmount);

        return view('content.service.users.livewire.index', compact('models'));
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

    public function updatedSelectedServices()
    {
        $this->selectedServices = array_filter($this->selectedServices,
            function ($selectedServices) {
                return $selectedServices !== false;
            }
        );
    }

    public function deleteConfirm($id)
    {
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => 'Weet je zeker dat je de service voor deze klant wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }

    #[On('delete')]
    public function delete($id)
    {        
        try {
            CustomerService::destroy($id);

        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De service voor de klant is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        return $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De service voor de klant is succesvol verwijderd',
            'text' => '',
        ]);
    }

    public function updateItem($customerID)
    {
        $this->selectedItem = $customerID;
        $this->edit = 1;
        $this->dispatch('getModelId', $this->selectedItem, $this->edit)->to('service.users.form');
        $this->dispatch('openModal');
    }

    public function showItem($customerID)
    {
        $this->selectedItem = $customerID;
        $this->edit = 0;
        $this->dispatch('getModelId', $this->selectedItem, $this->edit)->to('service.users.form');
        $this->dispatch('openModal');
    }

    public function sendInvitationMailConfirmation($itemID)
    {
        $this->dispatch('swal:confirm-send', [
            'type' => 'warning',
            'title' => 'Weet je zeker dat je de klant wil uitnodigen voor de APK?',
            'text' => 'De klant krijgt automatisch een e-mail met de uitnodiging aan het begin van de maand waarin de APK plaats vindt.',
            'id' => $itemID,
        ]);
    }

    public function sendInvitationMail($itemID)
    {       
        $business_email = Setting::where('name','business_email')->first()->value ?? 'info@deitdokter.nl';
        
        $service = CustomerService::with('customer')->findOrFail($itemID);

        try {
            DB::transaction(function () use ($service, $business_email) {
                Mail::to($service->customer->email ?? $business_email)
                    ->queue(new ApkInvitationMail($service->customer->id));
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De service voor de klant is niet aangepast',
                'text' => $e->getMessage(),
            ]);
        }
        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De e-mail is succesvol naar de klant gestuurd!',
            'text' => 'E-mailadres: ' . ($service->customer->email ?? $business_email),
        ]);
    }
}
