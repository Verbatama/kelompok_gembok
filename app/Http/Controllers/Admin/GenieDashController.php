<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GenieCredentialsDash;
use App\Services\GenieDash\GenieACS;
use App\Services\GenieDash\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GenieDashController extends Controller
{
    public function genieDash()
    {
        return view('admin.genieacs.dashboard');
    }

    public function configuration()
    {
        return view('admin.genieacs.configuration');
    }

    public function devices()
    {
        return view('admin.genieacs.devices');
    }

    public function devicesDetail(string $device)
    {
        return view('admin.genieacs.devicesDetail', compact('device'));
    }

    public function map()
    {
        $res = Http::get('http://163.223.104.166:8881/get-devices-config.php');

        $genieacsConfigured = true;

        if ($res->successful()) {
            $genieacsConfigured = $res->json('configured');
        }

        return view('admin.genieacs.map', [
            'genieacsConfigured' => $genieacsConfigured,
        ]);
    }
}
