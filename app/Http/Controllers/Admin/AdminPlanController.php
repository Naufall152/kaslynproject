<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class AdminPlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('price')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:plans,key',
            'name' => 'required|string',
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
            'features' => 'nullable|string', // input textarea, dipisah newline
        ]);

        $features = [];
        if (!empty($data['features'])) {
            $features = array_values(array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $data['features']))));
        }

        Plan::create([
            'key' => $data['key'],
            'name' => $data['name'],
            'price' => $data['price'],
            'duration_days' => $data['duration_days'],
            'is_active' => (bool)($data['is_active'] ?? false),
            'features' => $features,
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan berhasil dibuat.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:plans,key,' . $plan->id,
            'name' => 'required|string',
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
            'features' => 'nullable|string',
        ]);

        $features = [];
        if (!empty($data['features'])) {
            $features = array_values(array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $data['features']))));
        }

        $plan->update([
            'key' => $data['key'],
            'name' => $data['name'],
            'price' => $data['price'],
            'duration_days' => $data['duration_days'],
            'is_active' => (bool)($data['is_active'] ?? false),
            'features' => $features,
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan berhasil diupdate.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan berhasil dihapus.');
    }
}
