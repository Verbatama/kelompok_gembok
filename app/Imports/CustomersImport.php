<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Customer([
            'username' => $row['username'] ?? null,
            'pppoe_username' => $row['pppoe_username'] ?? null,
            'pppoe_password' => $row['pppoe_password'] ?? null,
            'static_ip' => $row['static_ip'] ?? null,
            'mac_address' => $row['mac_address'] ?? null,
            'name' => $row['name'] ?? null,
            'phone' => $row['phone'] ?? null,
            'email' => $row['email'] ?? null,
            'address' => $row['address'] ?? null,
            'latitude' => $row['latitude'] ?? null,
            'longitude' => $row['longitude'] ?? null,
            'package_id' => $row['package_id'] ?? null,
            'status' => $row['status'] ?? null,
            'join_date' => $row['join_date'] ?? null,
        ]);
    }
}
