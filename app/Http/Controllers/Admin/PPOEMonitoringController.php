<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Radacct;
use App\Models\Customer;

class PPOEMonitoringController extends Controller
{

    public function index()
    {
        $activeUsers = Radacct::whereNull('acctstoptime')
            ->pluck('username')
            ->flip();

        $customers = Customer::with('package')
            ->get()
            ->map(function ($customer) use ($activeUsers) {

                $customer->online =
                    isset($activeUsers[$customer->pppoe_username]);

                return $customer;
            });

        return view(
            'admin.monitoring.index',
            compact('customers')
        );
    }
}







