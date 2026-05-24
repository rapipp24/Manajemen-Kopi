<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWarehouseEmployeeRequest;
use App\Http\Requests\Admin\UpdateWarehouseEmployeeRequest;
use App\Models\WarehouseEmployee;
use Illuminate\Http\Request;

class WarehouseEmployeeController extends Controller
{
    /**
     * Display a listing of warehouse employees.
     */
    public function index(Request $request)
    {
        $query = WarehouseEmployee::query()->orderBy('is_active', 'desc')->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . trim($request->search) . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif' ? true : false);
        }

        $employees = $query->paginate(20)->withQueryString();

        return view('admin.warehouse-employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new warehouse employee.
     */
    public function create()
    {
        return view('admin.warehouse-employees.create');
    }

    /**
     * Store a newly created warehouse employee.
     */
    public function store(StoreWarehouseEmployeeRequest $request)
    {
        WarehouseEmployee::create($request->validated());

        return redirect()
            ->route('admin.warehouse-employees.index')
            ->with('success', 'Karyawan gudang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified warehouse employee.
     */
    public function edit(WarehouseEmployee $warehouseEmployee)
    {
        return view('admin.warehouse-employees.edit', compact('warehouseEmployee'));
    }

    /**
     * Update the specified warehouse employee.
     */
    public function update(UpdateWarehouseEmployeeRequest $request, WarehouseEmployee $warehouseEmployee)
    {
        $warehouseEmployee->update($request->validated());

        return redirect()
            ->route('admin.warehouse-employees.index')
            ->with('success', 'Data karyawan gudang berhasil diperbarui.');
    }

    /**
     * Deactivate or delete the specified warehouse employee.
     *
     * Jika sudah punya riwayat absensi, karyawan dinonaktifkan (is_active = false).
     * Jika belum punya absensi, dihapus permanen.
     */
    public function destroy(WarehouseEmployee $warehouseEmployee)
    {
        if ($warehouseEmployee->attendances()->exists()) {
            // Karyawan sudah punya riwayat absensi — nonaktifkan saja
            $warehouseEmployee->update(['is_active' => false]);

            return redirect()
                ->route('admin.warehouse-employees.index')
                ->with('success', 'Karyawan gudang dinonaktifkan karena sudah memiliki riwayat absensi.');
        }

        // Belum ada riwayat absensi — hapus permanen
        $warehouseEmployee->delete();

        return redirect()
            ->route('admin.warehouse-employees.index')
            ->with('success', 'Karyawan gudang berhasil dihapus.');
    }
}
