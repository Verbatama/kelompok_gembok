<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Odp;
use App\Models\Order;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TechnicianController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $technician = Technician::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if ($technician && Hash::check($request->password, $technician->password)) {
            
            session(['technician_id' => $technician->id]);
            return redirect()->route('technician.dashboard');
        }

        return back()->with('error', 'Username/Email atau password salah');
    }

    public function logout()
    {
        session()->forget('technician_id');
        
        return redirect()->route('technician.login');
    }

    public function dashboard()
    {
        // $technician = Technician::find(session('technician_id'));
        $technician = Technician::where('id', session('technician_id'))->first();

        $technicians = Technician::all();

        $todayTasks = Order::whereDate('created_at', today())
            ->where('technician_id', $technician->id)
            ->count();

        $completedTasks = Order::where('technician_id', $technician->id)
            ->where('status', 'completed')
            ->count();

        $pendingTasks = Order::where('technician_id', $technician->id)
            ->where('status', 'pending')
            ->count();

        $monthTasks = Order::whereMonth('created_at', now()->month)
            ->where('technician_id', $technician->id)
            ->count();

        $tasks = Order::where('technician_id', $technician->id)
            ->latest()
            ->take(10)
            ->get();

        // For now, using placeholder data since we don't have a Task model yet

        return view('technician.dashboard', compact(
            'technician', 'todayTasks', 'completedTasks',
            'pendingTasks', 'monthTasks', 'tasks'
        ));
    }

    public function tasks(Request $request)
{
    $technician = Technician::find(session('technician_id'));

    $tasks = Order::with('customer')
        ->where('technician_id', $technician->id)
        ->when($request->status, function ($query, $status) {
            $query->where('status', $status);
        })
        ->latest()
        ->paginate(10);

    return view('technician.tasks', compact('technician', 'tasks'));
}

    public function showTask($taskId)
    {
        $technician = Technician::find(session('technician_id'));
        $task = Order::where('id', $taskId)
            ->where('technician_id', $technician->id)
            ->firstOrFail();

        return view('technician.task-detail', compact('technician', 'task'));
    }

    public function updateTask(Request $request, $taskId)
    {
        //TODO: Implement task update logic
        return back()->with('success', 'Status tugas berhasil diperbarui');
    }

    public function installations()
    {
        $technician = Technician    ::find(session('technician_id'));

        // Get customers pending installation
        $installations = Order::where('status', 'installing')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

       
        return view('technician.installations', compact('technician', 'installations'));
    }

    public function repairs(    )
    {
        $technician = Technician::find(session('technician_id'));

        // Get customers with issues (suspended or reported)
        $repairs = Customer::where('status', 'suspended')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('technician.repairs', compact('technician', 'repairs'));
    }

    public function map()
    {
        $technician = Technician::find(session('technician_id'));

        // Get ODPs with coordinates
        $odps = Odp::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Get customers with coordinates
        $customers = Customer::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('technician.map', compact('technician', 'odps', 'customers'));
    }

    public function profile()
    {
        $technician = Technician::find(session('technician_id'));
        return view('technician.profile', compact('technician'));
    }
}
