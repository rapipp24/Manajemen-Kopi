<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Http\Requests\Admin\StoreRawMaterialRequest;
use App\Http\Requests\Admin\UpdateRawMaterialRequest;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = RawMaterial::with('unit')->orderBy('name', 'asc');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $materials = $query->paginate(15);
        return view('admin.raw-materials.index', compact('materials'));
    }

    public function create()
    {
        $units = Unit::where('is_active', true)->get();
        return view('admin.raw-materials.create', compact('units'));
    }

    public function store(StoreRawMaterialRequest $request)
    {
        $data = $request->validated();
        
        // Generate Auto Code: BB-0001
        $lastMaterial = RawMaterial::withTrashed()->orderBy('id', 'desc')->first();
        $nextNumber = $lastMaterial ? $lastMaterial->id + 1 : 1;
        $data['code'] = 'BB-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        RawMaterial::create($data);

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Bahan baku berhasil ditambahkan! (Kode: ' . $data['code'] . ')');
    }

    public function edit(RawMaterial $rawMaterial)
    {
        $units = Unit::where('is_active', true)->get();
        return view('admin.raw-materials.edit', compact('rawMaterial', 'units'));
    }

    public function update(UpdateRawMaterialRequest $request, RawMaterial $rawMaterial)
    {
        $rawMaterial->update($request->validated());

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Bahan baku berhasil diperbarui!');
    }

    public function destroy(RawMaterial $rawMaterial)
    {
        // Check if material is used in transactions before deleting (optional/future)
        // For now, we allow soft-delete via is_active toggle in edit, 
        // but destroy will hard delete if allowed.
        $rawMaterial->delete();

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Bahan baku berhasil dihapus!');
    }

    public function movements(RawMaterial $rawMaterial)
    {
        $movements = StockMovement::where('item_type', 'raw_material')
            ->where('item_id', $rawMaterial->id)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.raw-materials.movements', compact('rawMaterial', 'movements'));
    }
}
