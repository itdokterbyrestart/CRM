<?php

namespace App\Livewire\Frontend\Quote;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Quote,
    Setting,
};
use Carbon\Carbon;

class ShowStatus extends Component
{
    public $quote_id, $quote, $quote_status, $selected_products, $selected_products_total, $expiration_date, $link_to_contact_page;
    public $quote_statuses;

    public $selectedCustomer;

    protected $listeners = [
        'refreshParent' => '$refresh',
    ];

    public function render()
    {
        $this->quote = Quote::with('quote_statuses','selected_quote_products')->findOrFail($this->quote_id);
        $this->quote_status = $this->quote->quote_statuses->first();
        $this->selected_products = $this->quote->selected_quote_products;
        $this->selected_products_total = number_format($this->quote->selected_quote_products->sum('total_price_customer_including_tax'), 2, '.', '');
        $this->expiration_date = Carbon::parse($this->quote->expiration_date ?? \Carbon\Carbon::now()->addDays(14))->format('d-m-Y');
        $this->link_to_contact_page = Setting::where('name','link_to_contact_page')->first()->value;
        return view('frontend.quote.livewire.showstatus');
    }

    public function acceptOrDenyQuote($accepted)
    {
        $this->dispatch('getModel', $this->quote->id, $accepted)->to('frontend.quote.form');
        $this->dispatch('openModal');
    }
}
