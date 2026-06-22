<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->paginate(10);

        return view('admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('admin.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'receipt' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('receipt')) {
            $validated['receipt'] = $request
                ->file('receipt')
                ->store('receipts', 'public');
        }

        Expense::create($validated);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense berhasil ditambahkan');
    }

    public function show(Expense $expense)
    {
        return view('admin.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        return view('admin.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'receipt' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('receipt')) {

            if ($expense->receipt) {
                Storage::disk('public')->delete($expense->receipt);
            }

            $validated['receipt'] = $request
                ->file('receipt')
                ->store('receipts', 'public');
        }

        $expense->update($validated);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense berhasil diperbarui');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->receipt) {
            Storage::disk('public')->delete($expense->receipt);
        }

        $expense->delete();

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense berhasil dihapus');
    }
}