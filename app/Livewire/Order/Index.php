<?php

namespace App\Livewire\Order;

use App\Mail\ApkInvitationMail;
use App\Mail\ScheduleAppointmentMail;
use Livewire\WithPagination;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Order,
    OrderStatus,
    Setting,
};

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginationItemsAmount = 30;

    public $selectedItem, $edit, $show_company_names = 1;
    public $search = '';
    public $sortColumn = 'order_statuses.order', $sortDirection = 'ASC';
    public $selectedStatuses = [], $order_statuses = [];

    public $selectedCustomer, $selectedOrder;

    public function mount()
    {
        if (isset($_GET['customer_id'])) {
            $this->selectedCustomer = $_GET['customer_id'];
        }

        if(isset($_GET['order_id'])) {
            $this->selectedOrder = $_GET['order_id'];
        }

        $this->order_statuses = OrderStatus::orderBy('order')->get();
        $this->show_company_names = Setting::where('name', 'show_company_in_customer_list')->first()->value ?? 1;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%'.$this->search.'%';

        $orders = Order::query()
            ->join('order_statuses','order_statuses.id','=','orders.order_status_id')
            ->join('customers','customers.id','=','orders.customer_id')
            ->with('order_status','customer')
            ->withCount('invoices');
            if(count($this->selectedStatuses) > 0) {
                $orders->whereHas('order_status', function ($q) {
                    $q->whereIn('id', array_keys($this->selectedStatuses));
                });
            }
            if (isset($this->selectedCustomer)) {
                $orders->where('orders.customer_id', $this->selectedCustomer);
            }
            if (isset($this->selectedOrder)) {
                $orders->where('orders.id', $this->selectedOrder);
            }
            $orders = $orders->where(function($q) use ($search) {
                $q->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('name','like',$search);
                })
                ->orWhere('title','like',$search);
                if (!strtotime($this->search)) {
                    $q
                    ->orWhereDay('orders.created_at',$this->search)
                    ->orWhereMonth('orders.created_at', $this->search)
                    ->orWhereYear('orders.created_at', $this->search)
                    ->orWhereDay('orders.updated_at',$this->search)
                    ->orWhereMonth('orders.updated_at', $this->search)
                    ->orWhereYear('orders.updated_at', $this->search);
                } else {
                    $date_search = date('Y-m-d',strtotime($this->search));
                    $q
                    ->orWhereDate('orders.created_at','=',$date_search)
                    ->orWhereDate('orders.updated_at','=',$date_search);
                }
            })
            ->withSum('order_hours', 'amount')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->orderBy('order_statuses.order', 'ASC')
            ->orderBy('updated_at', 'DESC')
            ->orderBy('title', 'ASC')
            ->paginate($this->paginationItemsAmount);

        return view('content.order.livewire.index', compact('orders'));
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

    public function updatedSelectedStatuses()
    {
        $this->selectedStatuses = array_filter($this->selectedStatuses,
            function ($selectedStatuses) {
                return $selectedStatuses !== false;
            }
        );
    }

    public function deleteConfirm($id)
    {
        if (!Auth::user()->can('delete order')) {
            abort(403);
        }

        $order = Order::withCount('invoices')->find($id);

        if ($order->invoices_count > 0) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Je kunt de opdracht niet verwijderen',
                'text' => 'Er is een factuur aan deze opdracht gekoppeld, verwijder deze eerst en probeer het opnieuw.',
            ]);
        }
        
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => 'Weet je zeker dat je deze opdracht wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }
    
    #[On('delete')]
    public function delete($id)
    {
        if (!Auth::user()->can('delete order')) {
            abort(403);
        }

        try{
            DB::transaction(function () use ($id) {
                Order::destroy($id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De opdracht is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De opdracht is succesvol verwijderd!',
            'text' => '',
        ]);

    }

    public function createInvoiceConfirm($order_id)
    {
        if (count(Order::find($order_id)->invoices) > 0) {
            return $this->dispatch('swal:invoice_found', [
                'type' => 'info',
                'title' => 'Factuur gevonden',
                'text' => 'Wil je nog een factuur maken of de huidige factuur bekijken?',
                'id' => $order_id,
            ]);
        };
        $this->createInvoice($order_id);
    }

    #[On('createInvoice')]
    public function createInvoice($order_id)
    {
        return redirect(route('invoice.create', ['order_id' => $order_id]));
    }

    #[On('showInvoice')]
    public function showInvoice($order_id)
    {
        $invoice = Order::findOrFail($order_id)->invoices()->orderBy('created_at','desc')->first();
        return redirect(route('invoice.show', $invoice->id));
    }

    #[On('sendAppointmentMailConfirmation')]
    public function sendAppointmentMailConfirmation($order_id, $email_type)
    {
        $order = Order::findOrFail($order_id);

        if (empty($order->customer->email)) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'E-mail niet gevonden',
                'text' => 'Er is nog geen e-mail toegevoegd aan deze klant',
            ]);
        }
        
        return $this->dispatch('swal:confirm_appointment', [
            'type' => 'info',
            'title' => 'Weet je zeker dat je een uitnodiging wil sturen naar deze klant?',
            'text' => 'E-mail: ' . $order->customer->email,
            'id' => $order_id,
            'email_type' => $email_type,
        ]);
    }

    #[On('sendAppointmentMail')]
    public function sendAppointmentMail($order_id, $email_type)
    {
        $order = Order::findOrFail($order_id);

        if ($email_type == 1) {
            Mail::to($order->customer->email)
                ->queue(new ScheduleAppointmentMail($order->id,0,0));
        } elseif ($email_type == 2) {
            Mail::to($order->customer->email)
                ->queue(new ApkInvitationMail($order->customer->id));
        }
        

        return $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Uitnodiging succesvol verstuurd',
            'text' => 'E-mail: ' . $order->customer->email,
        ]);
    }
}
