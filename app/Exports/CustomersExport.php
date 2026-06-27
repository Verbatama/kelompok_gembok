<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        public $status = null
    ) {}

    public function collection()
    {
        $query = Customer::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function map($customer): array
    {
        return [
            // $customer->id,
            $customer->username,
            $customer->pppoe_username,
            $customer->pppoe_password,
            $customer->static_ip,
            $customer->mac_address,
            $customer->name,
            $customer->phone,
            $customer->email,
            $customer->address,
            $customer->package_id,
            $customer->status,
            $customer->join_date,
            $customer->created_at,
            $customer->updated_at,
            $customer->latitude,
            $customer->longitude,
        ];
    }

    public function headings(): array
    {
        return [
            // 'id',
            'username',
            'pppoe_username',
            'pppoe_password',
            'static_ip',
            'mac_address',
            'name',
            'phone',
            'email',
            'address',
            'package_id',
            'status',
            'join_date',
            'created_at',
            'updated_at',
            'latitude',
            'longitude',
        ];
    }
}