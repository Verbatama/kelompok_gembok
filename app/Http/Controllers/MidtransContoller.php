<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use Midtrans\Notification;
class MidtransContoller extends Controller
{
    public function pay(Invoice $invoice, MidtransService $midtrans)
    {
        $transaction = $midtrans->createTransaction($invoice);

        $invoice->update([
            'payment_gateaway' => 'midtrans',
            'payment_order_id' => $invoice->invoice_number . '-' . time(),
            'payment_url' => $transaction->redirect_url,
            'status' => 'pending',
        ]);

        return redirect($transaction->redirect_url);
    }

    public function notification(Request $request)
    {
        $notification = new Notification();

        $orderId = $notification->order_id;
        $status = $notification->transaction_status;


        $invoice = Invoice::where('payment_order_id', $orderId)
            ->first();


        if (!$invoice) {
            return response()->json([
                'message' => 'Invoice not found'
            ], 404);
        }


        if ($status == 'settlement' || $status == 'capture') {

            $invoice->update([
                'status' => 'paid'
            ]);

        } elseif ($status == 'pending') {

            $invoice->update([
                'status' => 'pending'
            ]);

        } elseif (
            in_array($status, [
                'deny',
                'expire',
                'cancel'
            ])
        ) {

            $invoice->update([
                'status' => 'unpaid'
            ]);

        }


        return response()->json([
            'message' => 'OK'
        ]);
    }
}
