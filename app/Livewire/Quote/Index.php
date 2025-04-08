<?php

namespace App\Livewire\Quote;
use Livewire\WithPagination;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    QuoteStatus,
    Quote,
    Setting,
    OrderStatus,
    Order,
    OrderProduct,
};

use Auth;
use App\Mail\QuoteMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginationItemsAmount = 30;

    public $selectedItem, $edit, $show_company_names;
    public $search = '';
    public $sortColumn = 'created_at', $sortDirection = 'DESC';
    public $selectedStatuses = [], $quote_statuses, $status_convert_to_order;

    public $selectedCustomer;

    public $show_page_view_count = true, $show_dates = true;

    public function mount()
    {
        if (isset($_GET['customer_id'])) {
            $this->selectedCustomer = $_GET['customer_id'];
        }

        $this->quote_statuses = QuoteStatus::orderBy('order')->get();

        $this->status_convert_to_order = $this->quote_statuses
            ->where('name', 'Akkoord')
            ->first();
        $this->show_company_names = Setting::where('name', 'show_company_in_customer_list')->first()->value;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%'.$this->search.'%';

        $quotes = Quote::query()
            ->with('quote_statuses','customer');
            if (isset($this->selectedCustomer)) {
                $quotes->where('customer_id', $this->selectedCustomer);
            }
            $quotes->where(function($q) use ($search) {
                $q->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('name','like',$search);
                })
                ->orWhere('title','like',$search);
                if (!strtotime($this->search)) {
                    $q
                    ->orWhereDay('quotes.created_at',$this->search)
                    ->orWhereMonth('quotes.created_at', $this->search)
                    ->orWhereYear('quotes.created_at', $this->search)
                    ->orWhereDay('quotes.updated_at',$this->search)
                    ->orWhereMonth('quotes.updated_at', $this->search)
                    ->orWhereYear('quotes.updated_at', $this->search)
                    ->orWhereDay('quotes.expiration_date',$this->search)
                    ->orWhereMonth('quotes.expiration_date', $this->search)
                    ->orWhereYear('quotes.expiration_date', $this->search);
                } else {
                    $date_search = date('Y-m-d',strtotime($this->search));
                    $q
                    ->orWhereDate('quotes.created_at','=',$date_search)
                    ->orWhereDate('quotes.expiration_date','=',$date_search)
                    ->orWhereDate('quotes.updated_at','=',$date_search);
                }
            });
            if ($this->sortColumn == 'sent_at') {
                $this->sortDirection == 'DESC' ? $quotes->orderBy('sent_to_customer','ASC')->orderBy('sent_at','DESC') : $quotes->orderBy('sent_to_customer','DESC')->orderBy('sent_at','ASC');
            } elseif ($this->sortColumn != 'customer' AND $this->sortColumn != 'quote_status') {
                $quotes->orderBy($this->sortColumn, $this->sortDirection);
            }
        $quotes = $quotes->paginate($this->paginationItemsAmount);


        if(count($this->selectedStatuses) > 0) {            
            foreach ($quotes as $index => $quote){
                if (!in_array($quote->quote_statuses->first()->id,array_keys($this->selectedStatuses))) {
                    $quotes->forget($index);
                }
            }
        }   

        return view('content.quote.livewire.index', compact('quotes'));
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
        if (!Auth::user()->can('delete quote')) {
            abort(403);
        }
        
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => 'Weet je zeker dat je deze offerte wilt verwijderen?',
            'text' => '',
            'id' => $id,
        ]);
    }
    
    #[On('delete')]
    public function delete($quote_id)
    {
        if (!Auth::user()->can('delete quote')) {
            abort(403);
        }

        try{
            DB::transaction(function () use ($quote_id) {
                Quote::destroy($quote_id);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De offerte is niet verwijderd',
                'text' => $e->getMessage(),
            ]);
        }

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De offerte is succesvol verwijderd!',
            'text' => '',
        ]);
    }

    #[On('send_quote_to_customer_confirm')]
    public function send_quote_to_customer_confirm($quote_id)
    {
        $quote = Quote::with('quote_products','product_groups','services')->find($quote_id);

        if (!$quote) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'De offerte is niet gevonden, probeer het opnieuw',
                'text' => '',
            ]);
        }

        if (count($quote->quote_products) === 0 AND count($quote->product_groups) === 0 AND count($quote->services) === 0) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Er zijn geen producten toegevoegd aan deze offerte',
                'text' => 'Voeg eerst een product toe aan de offerte voordat je deze naar de klant verstuurt.',
            ]);
        }

        if ($quote->sent_to_customer === 1) {
            return $this->dispatch('swal:modal.confirm.send', [
                'type' => 'warning',
                'title' => 'Er is al een offerte gestuurd op ' . (date("d-m-Y",strtotime($quote->sent_at))),
                'text' => 'Weet je zeker dat je de offerte nogmaals wilt versturen?',
                'id' => $quote->id,
            ]);
        }

        $customer = $quote->customer;

        if($customer->email === null) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Er is geen e-mailadres toegevoegd aan deze klant',
                'text' => '',
            ]);
        }

        return $this->send_quote_to_customer($quote->id);
    }

    #[On('send_quote_to_customer')]
    public function send_quote_to_customer($quote_id)
    {
        $quote = Quote::find($quote_id);

        $customer = $quote->customer;

        try{
            DB::transaction(function () use ($quote, $customer) {
                $status = QuoteStatus::where('name', 'Wachten op klant')->first();

                Mail::to($customer->email)
                    ->send((new QuoteMail($quote->id)));
    
                $quote->update([
                    'sent_to_customer' => 1,
                    'sent_at' => Carbon::now(),
                ]);

                if ($quote->quote_statuses->first()->name != 'Akkoord') {
                    $quote->quote_statuses()->attach($status, [
                        'comment' => 'Offerte e-mail verstuurd op ' . (date("d-m-Y",strtotime($quote->sent_at))),
                    ]);
                }
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De offerte is niet verstuurd',
                'text' => $e->getMessage(),
            ]);
        }

        return $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'De offerte is succesvol naar ' . $customer->name . ' gestuurd',
            'text' => 'E-mail: ' . $customer->email,
        ]);
    }

    public function get_order($itemID)
    {
        $this->selectedItem = $itemID;
        
        $quote = Quote::find($this->selectedItem);

        if ($quote->quote_statuses->first()->id !== $this->status_convert_to_order->id) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'De offerte moet de status akkoord hebben',
                'text' => '',
            ]);
        }

        if ($quote->order_id === NULL) {
            return $this->dispatch('swal:modal.convert', [
                'type' => 'info',
                'title' => 'Wil je een opdracht aanmaken voor deze offerte?',
                'text' => '',
                'id' => $quote->id,
            ]);
        }

        return redirect()->route('order.index', ['order_id' => $quote->order_id],);
    }

    #[On('convert_quote_to_order')]
    public function convert_quote_to_order($quote_id, $deposit)
    {
        $quote = Quote::with('quote_products','product_groups','quote_statuses','selected_quote_products')->findOrFail($quote_id);
        
        $convert_status = OrderStatus::where('name','Nog doen')->first();

        $title = $quote->title;

        try{
            DB::transaction(function () use ($convert_status, $quote, &$order, $deposit, $title) {
                $order = Order::create([
                    'title' => $title,
                    'description' => $quote->description,
                    'customer_id' => $quote->customer->id,
                    'order_status_id' => $convert_status->id ?? 1,
                    'total_price_customer_excluding_tax' => 0,
                    'total_tax_amount' => 0,
                    'total_price_customer_including_tax' => 0,
                    'total_purchase_price_excluding_tax' => 0,
                    'total_profit' => 0,
                    'created_at' => Carbon::now(),
                ]);

                $array_total_price_customer_excluding_tax = [];
                $array_total_tax_amount = [];
                $array_total_price_customer_including_tax = [];
                $array_total_purchase_price_excluding_tax = [];
                $array_total_profit = [];
                $product_order = 1;

                foreach ($quote->selected_quote_products as $selected_quote_product) {
                    $total_purchase_price_excluding_tax_product = ($selected_quote_product->purchase_price_excluding_tax * $selected_quote_product->amount);
                    $revenue = $selected_quote_product->total_price_customer_excluding_tax;
                    $profit = $revenue - $total_purchase_price_excluding_tax_product;

                    if ($deposit == 1 && $selected_quote_product->price_customer_excluding_tax > 5) {
                        $deposit_percentage_amount = (float)(Setting::where('name', 'deposit_percentage_amount')->first()->value / 100);

                        OrderProduct::create([
                            'name' => 'Aanbetaling ' . $selected_quote_product->name . ((Str::contains($quote->description, 'Deluxe DJ Show') && Str::contains($selected_quote_product->name, ['Bruiloft','Feest'])) ? ' - Deluxe DJ Show' : ((Str::contains($quote->description, 'Premium DJ Show') && Str::contains($selected_quote_product->name, ['Bruiloft','Feest'])) ? ' - Premium DJ Show' : ''))  . (($deposit == 1 ) ? ' (' . $deposit_percentage_amount * 100 . '%)' : ''),
                            'purchase_price_excluding_tax' => $selected_quote_product->purchase_price_excluding_tax * $deposit_percentage_amount,
                            'purchase_price_including_tax' => $selected_quote_product->purchase_price_including_tax * $deposit_percentage_amount,
                            'price_customer_excluding_tax' => $selected_quote_product->price_customer_excluding_tax * $deposit_percentage_amount,
                            'price_customer_including_tax' => $selected_quote_product->price_customer_including_tax * $deposit_percentage_amount,
                            'amount' => $selected_quote_product->amount,
                            'revenue' => $revenue * $deposit_percentage_amount,
                            'profit' => ($profit ?? 0) * $deposit_percentage_amount,
                            'total_price_customer_including_tax' => $selected_quote_product->total_price_customer_including_tax  * $deposit_percentage_amount,
                            'tax_percentage' => $selected_quote_product->tax_percentage,
                            'description' => trim($selected_quote_product->description, " \n\r\t\v\0") ?? null,
                            'order_id' => $order->id,
                            'total_purchase_price_excluding_tax' => ($total_purchase_price_excluding_tax_product ?? 0) * $deposit_percentage_amount,
                            'order' => $product_order++,
                        ]);
                    } else {
                        $deposit_percentage_amount = (float)0.00;
                    }

                    $deposit_percentage_amount = (float)1 - $deposit_percentage_amount;

                    OrderProduct::create([
                        'name' => $selected_quote_product->name . ((Str::contains($quote->description, 'Deluxe DJ Show') && Str::contains($selected_quote_product->name, ['Bruiloft','Feest'])) ? ' - Deluxe DJ Show' : ((Str::contains($quote->description, 'Premium DJ Show') && Str::contains($selected_quote_product->name, ['Bruiloft','Feest'])) ? ' - Premium DJ Show' : '')) . (($deposit == 1) ? ' (' . $deposit_percentage_amount * 100 . '%)' : ''),
                        'purchase_price_excluding_tax' => $selected_quote_product->purchase_price_excluding_tax * $deposit_percentage_amount,
                        'purchase_price_including_tax' => $selected_quote_product->purchase_price_including_tax * $deposit_percentage_amount,
                        'price_customer_excluding_tax' => $selected_quote_product->price_customer_excluding_tax * $deposit_percentage_amount,
                        'price_customer_including_tax' => $selected_quote_product->price_customer_including_tax * $deposit_percentage_amount,
                        'amount' => $selected_quote_product->amount,
                        'revenue' => $revenue * $deposit_percentage_amount,
                        'profit' => ($profit ?? 0) * $deposit_percentage_amount,
                        'total_price_customer_including_tax' => $selected_quote_product->total_price_customer_including_tax * $deposit_percentage_amount,
                        'tax_percentage' => $selected_quote_product->tax_percentage,
                        'description' => trim($selected_quote_product->description, " \n\r\t\v\0") ?? null,
                        'order_id' => $order->id,
                        'total_purchase_price_excluding_tax' => ($total_purchase_price_excluding_tax_product ?? 0) * $deposit_percentage_amount,
                        'order' => $product_order++,
                        'created_at' => Carbon::now(),
                    ]);

                    $array_total_price_customer_excluding_tax[] = number_format((float)$revenue, 2, '.', '');
                    $array_total_tax_amount[] = number_format((float)$selected_quote_product->total_price_customer_including_tax - (float)$revenue, 2, '.', '');
                    $array_total_price_customer_including_tax[] = number_format((float)$selected_quote_product->total_price_customer_including_tax, 2, '.', '');
                    $array_total_purchase_price_excluding_tax[] = number_format((float)$total_purchase_price_excluding_tax_product ?? 0, 2, '.', '');
                    $array_total_profit[] = number_format((float)$profit, 2, '.', '');
                }

                $order->update([
                    'total_price_customer_excluding_tax' => number_format(array_sum($array_total_price_customer_excluding_tax), 2, '.', ''),
                    'total_tax_amount' => number_format(array_sum($array_total_tax_amount), 2, '.', ''),
                    'total_price_customer_including_tax' => number_format(array_sum($array_total_price_customer_including_tax), 2, '.', ''),
                    'total_purchase_price_excluding_tax' => number_format(array_sum($array_total_purchase_price_excluding_tax), 2, '.', ''),
                    'total_profit' => number_format(array_sum($array_total_profit), 2, '.', ''),
                ]);

                Quote::where('id',$quote->id)->update([
                    'order_id' => $order->id,
                ]);
            });
        } catch(\Exception $e) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Fout! De opdracht is niet aangemaakt/bewerkt',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect(route('order.edit', $order->id));
    }

    public function clone($quote_id)
    {
        $quote = Quote::find($quote_id)->clone();
        return redirect(route('quote.edit', $quote));
    }
}
