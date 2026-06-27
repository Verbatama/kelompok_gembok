<?php
    namespace App\Services;

    use App\Models\Invoice;
    use Midtrans\Config;
    use Midtrans\Snap;

    class MidtransService
    {
        public function __construct()
        {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;
        }

        public function createTransaction(Invoice $invoice)
        {
            $params = [
                'transaction_details' => [
                    'order_id' => $invoice->invoice_number.time(),
                    'gross_amount' => $invoice->total_amount,
                ],

                'customer_details' => [
                    'first_name' => $invoice->customer->name,
                    'email' => $invoice->customer->email,
                    'phone' => $invoice->customer->phone,
                ],
            ];

            return Snap::createTransaction($params);
        }
    }