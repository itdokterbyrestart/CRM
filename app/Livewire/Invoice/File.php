<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    Invoice,
};
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;

class File extends Component
{
    public $modelId, $files = [];

    #[On('getModelId')]
    public function getModelId($modelId)
    {
        $this->modelId = $modelId;
    }   

    public function downloadFile($itemID)
    {
        $file = $this->files->where('id', $itemID)->first();
        return response()->download($file->getPath(), $file->name . '.docx');
    }

    public function confirmDelete($itemID, $invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
        if ($invoice->invoice_statuses->first()->name == 'Betaald') {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Je kunt deze factuur niet verwijderen',
                'text' => 'De factuur is reeds betaald',
            ]);
        }
        
        $this->dispatch('swal:confirm-file', [
            'type' => 'warning',
            'title' => 'Weet je zeker dat je dit bestand wilt verwijderen?',
            'text' => '',
            'id' => $itemID,
        ]);
    }

    #[On('delete')]
    public function delete($id)
    {
        $file = $this->files->where('id', $id)->first();
        $delete = $file->delete();
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

    #[On('createInvoiceFile')]
    public function createInvoiceFile()
    {
        $invoice = Invoice::with('order.customer','order_products','order_hours','order.order_products')->findOrFail($this->modelId);
        $customer = $invoice->order->customer;
        $company_name = config('app.name') ?? 'De IT Dokter';

        if (!isset($customer->name) OR !isset($customer->street) OR !isset($customer->number) OR !isset($customer->postal_code) OR !isset($customer->place_name)){
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Het document is niet gemaakt',
                'text' => 'Er ontbreken klantgegevens. Vul alle gegevens in en probeer het opnieuw.',
            ]);
        }
        if (!isset($invoice->invoice_number) OR !isset($invoice->invoice_date) OR !isset($invoice->expiration_date)){
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Het document is niet gemaakt',
                'text' => 'Controleer de factuurgegevens en probeer het opnieuw',
            ]);
        }

        $invoice_data = [];

        $total_price_excluding_tax = (float)$invoice->total_price_customer_excluding_tax;
        $total_tax_amount = (float)$invoice->total_tax_amount;
        $total_price_including_tax = (float)$invoice->total_price_customer_including_tax;

        // Check if values are correct
        if (($total_price_excluding_tax + $total_tax_amount) != $total_price_including_tax){
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Het document is niet gemaakt',
                'text' => 'De prijzen kloppen niet, pas de factuur aan.',
            ]);
        }

        foreach ($invoice->order_products as $product) {
            $invoice_data[] = [
                'date' => $invoice->order->order_products->where('id', $product->id)->first()->created_at->format('d-m-Y'),
                'description' => is_null($product->pivot->comment) ? $product->name : $product->name . '<w:br/>' . $product->pivot->comment,
                'amount' => number_format($product->amount,2,',',''),
                'price' => number_format(($invoice->calculation_method_excluding_tax == false ? $product->price_customer_including_tax : $product->price_customer_excluding_tax),2,',',''),
                'tax_percentage' => number_format($product->tax_percentage,0,',',''),
                'subtotal' => number_format(($invoice->calculation_method_excluding_tax == false ? $product->total_price_customer_including_tax : $product->revenue),2,',',''),
            ];
        }
        
        foreach ($invoice->order_hours as $hour) {
            $invoice_data[] = [
                'date' => Carbon::parse($hour->date)->format('d-m-Y'),
                'description' => $hour->pivot->comment . ' (uurbasis)',
                'amount' => number_format($hour->amount,2,',',''),
                'price' => number_format(($invoice->calculation_method_excluding_tax == false ? $hour->price_customer_including_tax : $hour->price_customer_excluding_tax),2,',',''),
                'tax_percentage' => number_format($hour->tax_percentage,0,',',''),
                'subtotal' => number_format(($invoice->calculation_method_excluding_tax == false ? $hour->amount_revenue_including_tax : $hour->amount_revenue_excluding_tax),2,',',''),
            ];
        }

        // Set correct number format
        $total_price_excluding_tax = number_format($total_price_excluding_tax, 2, ',', '');
        $total_tax_amount = number_format($total_tax_amount, 2, ',', '');
        $total_price_including_tax = number_format($total_price_including_tax, 2, ',', '');

        // Get invoice dates
        $invoice_date = Carbon::parse($invoice->invoice_date);
        $expiration_date = Carbon::parse($invoice->expiration_date);
        $amount_of_days = $invoice_date->diffInDays($expiration_date) ?? (string)14;

        $extra_invoice_data = str_replace("\n", "</w:t><w:br/><w:t>", $invoice->extra_invoice_data);

        // Create document
        $templateProcessor = new TemplateProcessor(Storage::disk('templates')->path('invoice/Factuur_overmaken_laravel_template.docx'));
        $templateProcessor->setValue('name', (strlen($customer->company) > 0 ? $customer->company . '</w:t><w:br/><w:t>' : '') . $customer->name);
        $templateProcessor->setValue('street', $customer->street);
        $templateProcessor->setValue('number', $customer->number);
        $templateProcessor->setValue('postal_code', $customer->postal_code);
        $templateProcessor->setValue('place_name', $customer->place_name);
        $templateProcessor->setValue('invoice_number', $invoice->invoice_number);
        $templateProcessor->setValue('invoice_date', $invoice_date->format('d-m-Y'));
        $templateProcessor->setValue('expiration_date', $expiration_date->format('d-m-Y'));
        $templateProcessor->cloneRowAndSetValues('date',$invoice_data);
        $templateProcessor->setValue('total_price_customer_excluding_tax', $total_price_excluding_tax);
        $templateProcessor->setValue('total_price_customer_including_tax', $total_price_including_tax);
        $templateProcessor->setValue('total_tax_amount', $total_tax_amount);
        $templateProcessor->setValue('extra_invoice_data', $extra_invoice_data);
        $templateProcessor->setValue('amount_of_days', $amount_of_days);

        
        $fileNameDocx = 'Factuur ' . $invoice->invoice_number . ' - ' . (strlen($customer->company) > 0 ? $customer->company : $customer->name) . ' - ' . $company_name . '.docx';
        $templateProcessor->saveAs($fileNameDocx);

        $result_docx = $invoice->addMedia($fileNameDocx)->toMediaCollection('invoice');

        $this->dispatch('refreshParent')->to('invoice.index');

        if (!$result_docx) {
            return $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Het document is niet gemaakt',
                'text' => '',
            ]);
        }
        return $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Het document is succesvol gemaakt',
            'text' => '',
        ]);
    }

    private function cleanVars()
    {
        $this->files = [];
        $this->modelId = null;
    }
    
    #[On('forcedCloseModal')]
    public function forcedCloseModal()
    {
        $this->cleanVars();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        if ($this->modelId) {
            $model = Invoice::with('invoice_statuses','order.customer')->findOrFail($this->modelId);
            $this->files = $model->getMedia('invoice');
        }
        return view('content.invoice.livewire.file');
    }
}
