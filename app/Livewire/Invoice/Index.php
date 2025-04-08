<?php

namespace App\Livewire\Invoice;

use App\Mail\{
    InvoiceMail,
    InvoiceReminderMail,
};
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Component;

use App\Models\{
    Invoice,
    InvoiceStatus,
    Setting,
};

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginationItemsAmount = 30;

    public $selectedItem, $edit, $show_company_names;
    public $search = '';
    public $sortColumn = 'invoice_number', $sortDirection = 'DESC';
    public $selectedStatuses = [], $invoice_statuses;

    public $selectedCustomer, $selectedInvoice;

    public $show_page_view_count = true, $show_prices = true, $show_dates = true;

    #[On('refreshParent')]
    public function refreshParent()
    {
        return $this->dispatch('$refresh');
    }

    public function mount()
    {
        if(isset($_GET['order_id'])) {
            $this->selectedOrder = $_GET['order_id'];
        }

        $this->invoice_statuses = InvoiceStatus::orderBy('order')->get();
        $this->show_company_names = Setting::where('name', 'show_company_in_customer_list')->first()->value;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%'.$this->search.'%';

        $invoices = Invoice::query()
            ->with('invoice_statuses','order.customer','order_products','order_hours','media')
            ->orWhere('invoice_number','like',$search)
            ->orWhere('order_id','like',$search)
            ->orWhereHas('order.customer', function ($q) use ($search){
                $q->where('name','like',$search);
            });
            if ($this->sortColumn == 'sent_at') {
                $this->sortDirection == 'DESC' ? $invoices->orderBy('sent_to_customer','ASC')->orderBy('sent_at','DESC') : $invoices->orderBy('sent_to_customer','DESC')->orderBy('sent_at','ASC');
            } elseif ($this->sortColumn != 'invoice_status') {
                $invoices->orderBy($this->sortColumn, $this->sortDirection);
            }
        $invoices = $invoices->paginate($this->paginationItemsAmount);

        if(count($this->selectedStatuses) > 0) {
            foreach ($invoices as $index => $invoice){
                if (!in_array($invoice->invoice_statuses->first()->id,array_keys($this->selectedStatuses))) {
                    $invoices->forget($index);
                }
            }
        }

        return view('content.invoice.livewire.index', compact('invoices'));
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
        if (!Auth::user()->can('delete invoice')) {
            abort(403);
        }

        $invoice = Invoice::find($id);
        if ($invoice->invoice_statuses->first()->name == 'Betaald') {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Je kunt deze factuur niet verwijderen',
                'text' => 'De factuur is reeds betaald',
            ]);
        }

        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => 'Weet je zeker dat je deze factuur wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }

    #[On('delete_invoice')]
    public function delete_invoice($id)
    {
        if (!Auth::user()->can('delete invoice')) {
            abort(403);
        }

        $delete = Invoice::destroy($id);

        if ($delete === 0) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'De factuur is niet verwijderd',
                'text' => 'Probeer het nog een keer',
            ]);
        } else {
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'De factuur is succesvol verwijderd!',
                'text' => '',
            ]);
        }
    }

    public function updateItem($itemID)
    {
        $this->selectedItem = $itemID;
        $this->edit = 1;
        $this->dispatch('getModelId', $this->selectedItem, $this->edit)->to('invoice.form');
        $this->dispatch('openModal');
    }

    public function showItem($itemID)
    {
        $this->selectedItem = $itemID;
        $this->edit = 0;
        $this->dispatch('getModelId', $this->selectedItem, $this->edit)->to('invoice.form');
        $this->dispatch('openModal');
    }

    public function showFileItem($itemID)
    {
        $this->selectedItem = $itemID;
        $this->dispatch('getModelId', $this->selectedItem)->to('invoice.file');
        $this->dispatch('openFileModal');
    }

    public function send_invoice_to_customer_confirm($itemID)
    {
        $invoice = Invoice::with('order_products','order_hours')->find($itemID);

        if (!$invoice) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'De factuur is niet gevonden, probeer het opnieuw',
                'text' => '',
            ]);
        }

        if (count($invoice->order_products) === 0 AND count($invoice->order_hours) === 0) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Er zijn items geselecteerd voor deze factuur',
                'text' => 'Voeg eerst een item toe aan de factuur voordat je deze naar de klant verstuurt.',
            ]);
        }

        if ($invoice->sent_to_customer === 1) {
            return $this->dispatch('swal:modal.confirm.send', [
                'type' => 'warning',
                'title' => 'Er is al een factuur gestuurd op ' . (date("d-m-Y",strtotime($invoice->sent_at))),
                'text' => 'Weet je zeker dat je de factuur nogmaals wilt versturen?',
                'id' => $invoice->id,
            ]);
        }

        $customer = $invoice->order->customer;

        if($customer->email === null) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Er is geen e-mailadres toegevoegd aan deze klant',
                'text' => '',
            ]);
        }

        return $this->send_invoice_to_customer($invoice->id);
    }

    #[On('send_invoice_to_customer')]
    public function send_invoice_to_customer($invoice_id)
    {

        $invoice = Invoice::find($invoice_id);

        $customer = $invoice->order->customer;

        try{
            DB::transaction(function () use ($invoice, $customer) {
                $status = InvoiceStatus::where('name', 'Wachten op betaling')->first();

                Mail::to($customer->email)
                    ->send((new InvoiceMail($invoice->id)));
    
                $invoice->update([
                    'sent_to_customer' => 1,
                    'sent_at' => Carbon::now(),
                ]);

                if ($invoice->invoice_statuses->first()->name != 'Betaald') {
                    $invoice->invoice_statuses()->attach($status, [
                        'comment' => 'Factuur e-mail verstuurd op ' . Carbon::now()->format('d-m-Y \o\m H:i'),
                    ]);
                }
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De factuur is niet verstuurd',
                'text' => $e->getMessage(),
            ]);
        }

        return $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De factuur is succesvol naar ' . $customer->name . ' gestuurd',
            'text' => 'E-mail: ' . $customer->email,
        ]);
    }

    public function send_reminder_to_customer($invoice_id)
    {
        $invoice = Invoice::with('invoice_statuses')->find($invoice_id);

        $customer = $invoice->order->customer;

        // Get the names of existing statuses
        $existingStatuses = $invoice->invoice_statuses->pluck('name')->toArray();

        foreach ($existingStatuses as $existingStatus) {
            if ($existingStatus == 'Herinnering 2') {
                $status = $this->invoice_statuses->where('name','Herinnering 3')->first();
                break;
            }
            if ($existingStatus == 'Herinnering 1') {
                $status = $this->invoice_statuses->where('name','Herinnering 2')->first();
                break;
            }
            if (end($existingStatuses) == $existingStatus) {
                $status = $this->invoice_statuses->where('name','Herinnering 1')->first();
            }
        }

        try{
            DB::transaction(function () use ($invoice, $customer, $status) {
                
                $invoice->invoice_statuses()->attach($status,['comment' => $status->name . ' verstuurd op ' . Carbon::now()->format('d-m-Y \o\m H:i')]);

                Mail::to($customer->email)
                    ->send((new InvoiceReminderMail($invoice->id)));

            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De factuur is niet verstuurd',
                'text' => $e->getMessage(),
            ]);
        }

        return $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De herinnering is succesvol naar ' . $customer->name . ' gestuurd',
            'text' => 'E-mail: ' . $customer->email,
        ]);
    }
}
