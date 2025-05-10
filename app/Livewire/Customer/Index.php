<?php

namespace App\Livewire\Customer;

use App\Mail\ApkInvitationMail;
use App\Mail\ScheduleAppointmentMail;
use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;

use App\Models\{
    Customer,
    Setting,
};
use Carbon\Carbon;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginationItemsAmount = 30;
    
    public $selectedItem, $edit;
    public $search = '';
    public $sortColumn = 'name', $sortDirection = 'ASC';

    public $with_order_created_at, $customer_with_order_created_at;


    #[On('refreshParent')]
    public function refreshParent()
    {
        return $this->dispatch('$refresh');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'with_order_created_at' => 'sometimes|date',
        ];
    }

    protected $validationAttributes = [
        'with_order_created_at' => 'datum',
    ];

    public function render()
    {
        $search = '%'.$this->search.'%';

        $customers = Customer::query();
            if (isset($this->customer_with_order_created_at)) {
                $customers->whereHas('orders', function ($q) {
                    $q->whereDate('created_at','>=',Carbon::parse($this->customer_with_order_created_at)->toDateString());
                });
            }
            $customers->where(function ($q) use ($search) {
                $q->Where('id','like',$search)
                ->orWhere('name','like',$search)
                ->orWhere('company','like',$search)
                ->orWhere('email','like',$search)
                ->orWhere('street','like',$search)
                ->orWhere('number','like',$search)
                ->orWhere('postal_code','like',$search)
                ->orWhere('place_name','like',$search)
                ->orWhere('discount','like',$search);
            });
            if (!strtotime($this->search)) {
                $customers
                ->orWhereDay('created_at',$this->search)
                ->orWhereMonth('created_at', $this->search)
                ->orWhereYear('created_at', $this->search)
                ->orWhereDay('updated_at',$this->search)
                ->orWhereMonth('updated_at', $this->search)
                ->orWhereYear('updated_at', $this->search);
            } else {
                $date_search = date('Y-m-d',strtotime($this->search));
                $customers
                ->orWhereDate('created_at','=',$date_search)
                ->orWhereDate('updated_at','=',$date_search);
            }
            
        $customers = $customers
            ->withCount(['services' => function ($q) {
                $q->where('name', 'APK op afstand')->orWhere('name', 'APK aan huis');
            }])
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->paginationItemsAmount);

        return view('content.customer.livewire.index', compact('customers'));
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

    public function deleteConfirm($id)
    {
        if (!Auth::user()->can('delete customer')) {
            abort(403);
        }
        
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => 'Weet je zeker dat je deze klant wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }
    
    #[On('delete_customer')]
    public function delete_customer($id)
    {
        if (!Auth::user()->can('delete customer')) {
            abort(403);
        }

        try{
            DB::transaction(function () use ($id) {
                Customer::destroy($id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De klant is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De klant is succesvol verwijderd!',
            'text' => '',
        ]);
    }

    public function update_with_order_created_at()
    {
        $this->validate();
        $this->customer_with_order_created_at = $this->with_order_created_at;
    }

    public function reset_with_order_created_at()
    {
        $this->customer_with_order_created_at = null;
        $this->with_order_created_at = null;
    }

    #[On('sendAppointmentMailConfirmation')]
    public function sendAppointmentMailConfirmation($customer_id, $email_type)
    {
        $customer = Customer::findOrFail($customer_id);

        if (empty($customer->email)) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'E-mail niet gevonden',
                'text' => 'Er is nog geen e-mail toegevoegd aan deze klant',
            ]);
        }
        
        return $this->dispatch('swal:confirm_appointment', [
            'type' => 'info',
            'title' => 'Weet je zeker dat je een ' . ($email_type == 2 ? 'APK ' : '') . 'uitnodiging wil sturen naar deze klant?',
            'text' => 'E-mail: ' . $customer->email,
            'id' => $customer_id,
            'email_type' => $email_type,
        ]);
    }

    #[On('sendAppointmentMail')]
    public function sendAppointmentMail($customer_id, $email_type)
    {
        $customer = Customer::findOrFail($customer_id);

        if ($email_type == 1) {
            Mail::to($customer->email)
                ->queue(new ScheduleAppointmentMail($customer->id,0,1));
        } elseif ($email_type == 2) {
            Mail::to($customer->email)
                ->queue(new ApkInvitationMail($customer->id));
        }
        

        return $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Uitnodiging succesvol verstuurd',
            'text' => 'E-mail: ' . $customer->email,
        ]);
    }
}
