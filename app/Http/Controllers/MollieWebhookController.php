<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\{
    InvoicePaidMail,
};
use Mollie\Laravel\Facades\Mollie;
use App\Models\{
    Invoice,
    InvoiceStatus,
    OrderStatus,
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class MollieWebhookController extends Controller
{
    public function handleWebhookNotification(Request $request) {
        $paymentId = $request->input('id');
        $payment = Mollie::api()->payments->get($paymentId);

        if ($payment->isPaid()) {
            $status = InvoiceStatus::where('name', 'Betaald')->first();
            $invoice = Invoice::find($payment->metadata->invoice_id);
            if ($invoice) {
                $invoice->invoice_statuses()->attach($status, ['comment' => 'Betaald op ' . Carbon::parse($payment->paidAt)->format('d-m-Y \o\m H:i') . ' via ' . $payment->method]);
                $invoice->update(['payment_id' => null]);
                Mail::to($invoice->order->customer->email)
                    ->send((new InvoicePaidMail($invoice->id, $paymentId)));
            }
        }
    }
}
