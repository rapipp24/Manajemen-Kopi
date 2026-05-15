<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Http\Requests\Admin\StoreUnitRequest;
use App\Http\Requests\Admin\UpdateUnitRequest;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Unit::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $units = $query->paginate(10);
        return view('admin.units.index', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        $unit = Unit::withTrashed()->where('code', $request->code)->first();

        if ($unit) {
            $unit->restore();
            $unit->update($request->validated());
            $message = 'Satuan berhasil dipulihkan dan diperbarui!';
        } else {
            Unit::create($request->validated());
            $message = 'Satuan berhasil ditambahkan!';
        }

        return redirect()->route('admin.units.index')->with('success', $message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $unit->update($request->validated());

        return redirect()->route('admin.units.index')->with('success', 'Satuan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('admin.units.index')->with('success', 'Satuan berhasil dihapus ke tong sampah!');
    }
}
