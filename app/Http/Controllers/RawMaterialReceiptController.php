<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\RawMaterialReceipt;
use App\Models\RawMaterialReceiptItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class RawMaterialReceiptController extends Controller
{
    public function index()
    {
        $receipts = RawMaterialReceipt::with('supplier', 'creator')
            ->orderBy('receipt_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.raw-material-receipts.index', compact('receipts'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $materials = RawMaterial::where('is_active', true)->with('unit')->get();
        
        return view('admin.raw-material-receipts.create', compact('suppliers', 'materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'receipt_date' => 'required|date',
            'reference_number' => 'nullable|string|max:50',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            // 1. Generate Receipt Number
            $date = date('Ymd');
            $count = RawMaterialReceipt::whereDate('created_at', today())->count() + 1;
            $receiptNumber = 'RCP-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // 2. Create Header
            $receipt = RawMaterialReceipt::create([
                'receipt_number' => $receiptNumber,
                'reference_number' => $request->reference_number,
                'supplier_id' => $request->supplier_id,
                'receipt_date' => $request->receipt_date,
                'note' => $request->note,
                'total_amount' => 0, // Will be updated after items
                'created_by' => Auth::id(),
            ]);

            $totalAmount = 0;
            
            // Consolidate identical items (same material and same price)
            $consolidatedItems = [];
            foreach ($request->items as $item) {
                $key = $item['raw_material_id'] . '_' . (float)$item['unit_price'];
                if (isset($consolidatedItems[$key])) {
                    $consolidatedItems[$key]['qty'] += (float)$item['qty'];
                } else {
                    $consolidatedItems[$key] = [
                        'raw_material_id' => $item['raw_material_id'],
                        'qty' => (float)$item['qty'],
                        'unit_price' => (float)$item['unit_price']
                    ];
                }
            }

            foreach ($consolidatedItems as $item) {
                $subtotal = $item['qty'] * $item['unit_price'];
                $totalAmount += $subtotal;

                // 3. Create Item
                RawMaterialReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);

                // 4. Update Material Stock
                $material = RawMaterial::lockForUpdate()->find($item['raw_material_id']);
                $stockBefore = $material->current_stock;
                $material->current_stock += $item['qty'];
                $material->save();

                // 5. Record Stock Movement
                StockMovement::create([
                    'item_type' => 'raw_material',
                    'item_id' => $material->id,
                    'movement_type' => 'in',
                    'reference_type' => RawMaterialReceipt::class,
                    'reference_id' => $receipt->id,
                    'qty' => $item['qty'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $material->current_stock,
                    'note' => 'Penerimaan bahan dari supplier. No: ' . $receiptNumber,
                ]);
            }

            // 6. Update Total Amount in Header
            $receipt->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('admin.raw-material-receipts.show', $receipt)
                ->with('success', 'Penerimaan bahan baku berhasil disimpan.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function show(RawMaterialReceipt $rawMaterialReceipt)
    {
        $receipt = $rawMaterialReceipt->load('supplier', 'creator', 'items.rawMaterial.unit');
        return view('admin.raw-material-receipts.show', compact('receipt'));
    }
}
