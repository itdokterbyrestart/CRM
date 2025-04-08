<?php

namespace App\Observers;

use App\Models\{
    Invoice,
    Order,
    Setting,
    OrderStatus,
};
use Carbon\Carbon;

class InvoiceObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Invoice "updated" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        $invoice = Invoice::with('invoice_statuses','order')->find($invoice->id);
        $invoice_status = $invoice->invoice_statuses->first()->name;
        $order_status = $invoice->order->order_status->name;
        $invoice_statuses_array_1 = [
            'Nog maken',
            'Controleren',
            'Wachten op versturen',
            'Geweigerd'
        ];
        $invoice_statuses_array_2 = [
            'Wachten op betaling',
            'Herinnering 1',
            'Herinnering 2',
            'Herinnering 3',
            'Verlopen',
        ];
        $deposit_enabled = Setting::where('name','enable_deposit')->first()->value ?? 0;

        if (($order_status == 'Wachten op betaling' OR $order_status == 'Betaald, archief') AND in_array($invoice_status, $invoice_statuses_array_1)) {
            $invoice->order->update(['order_status_id' => OrderStatus::where('name', 'Factureren')->first()->id]);
        // Als orderstatus is factureren of betaald, archief en invoice status is wachten op betaling dan wachten op betaling
        } elseif (($order_status == 'Factureren' OR $order_status == 'Betaald, archief') AND in_array($invoice_status, $invoice_statuses_array_2)) {
            $invoice->order->update(['order_status_id' => OrderStatus::where('name', 'Wachten op betaling')->first()->id]);
        } elseif ($invoice_status == 'Betaald' AND $order_status != 'Betaald, archief' AND $deposit_enabled == 0) {
            $invoice->order->update(['order_status_id' => OrderStatus::where('name', 'Betaald, archief')->first()->id]);
        } elseif ($invoice_status == 'Betaald' AND $deposit_enabled == 1) {
            if (Carbon::parse($invoice->order->created_at) > Carbon::now()) {
                $invoice->order->update(['order_status_id' => OrderStatus::where('name', 'Optreden gepland')->first()->id]);
            } else {
                $invoice->order->update(['order_status_id' => OrderStatus::where('name', 'Betaald, archief')->first()->id]);
            }
        }
    }

    /**
     * Handle the Invoice "deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        $invoice->order->update(['order_status_id' => OrderStatus::where('name', 'Factureren')->first()->id]);
    }
}
