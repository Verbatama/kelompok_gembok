<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;

class CustomerExportController extends Controller
{
    public function index(Request $request)
    {
        return view("admin.database.export");
    }


    public function export(Request $request)
    {
        $format = $request->input('format', 'xlsx');


        $extension = $format == 'csv'
            ? 'csv'
            : 'xlsx';


        return Excel::download(
            new CustomersExport(
                $request->input('status')
            ),
            'customers.' . $extension
        );
    }
}
