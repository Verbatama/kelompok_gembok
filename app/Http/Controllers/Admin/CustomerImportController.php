<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Imports\CustomersImport;
    use Maatwebsite\Excel\Facades\Excel;

    class CustomerImportController extends Controller
    {
        public function index(Request $request)
        {
            return view("admin.database.import");
        }

        public function store(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:xlsx,csv'
            ]);

            try {
                Excel::import(
                    new CustomersImport,
                    $request->file('file')
                );

                return back()->with('success', 'Import berhasil');

            } catch (\Exception $e) {

                dd($e->getMessage());

            }
        }
    }
