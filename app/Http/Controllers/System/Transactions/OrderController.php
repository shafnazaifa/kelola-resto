<?php

namespace App\Http\Controllers\System\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function order(Request $request){
        // Log incoming request for debugging
        \Illuminate\Support\Facades\Log::info('Order request received:', [
            'all_data' => $request->all(),
            'has_orders' => $request->has('orders'),
            'orders_data' => $request->get('orders'),
            'orders_is_array' => is_array($request->get('orders'))
        ]);
        
        // Support creating multiple order items in one request
        if ($request->has('orders') && is_array($request->get('orders'))) {
            $validator = Validator::make($request->all(), [
                'id_meja' => 'required|exists:mejas,id',
                'id_pelanggan' => 'required|exists:pelanggans,id',
                'orders' => 'required|array|min:1',
                'orders.*.id_menu' => 'required|exists:menus,id',
                'orders.*.jumlah' => 'nullable|integer|min:1',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $createdOrders = [];
            foreach ($request->orders as $index => $item) {
                \Illuminate\Support\Facades\Log::info("Creating order {$index}:", $item);
                
                $pesanan = Pesanan::create([
                    'id_menu' => $item['id_menu'],
                    'id_meja' => $request->id_meja,
                    'id_pelanggan' => $request->id_pelanggan,
                    'jumlah' => isset($item['jumlah']) ? (int) $item['jumlah'] : 1,
                    'id_user' => Auth::user()->id,
                ]);
                
                $createdOrders[] = $pesanan->id;
                \Illuminate\Support\Facades\Log::info("Order created with ID: {$pesanan->id}");
            }
            
            \Illuminate\Support\Facades\Log::info('Total orders created:', $createdOrders);
            
            // Update table status to occupied
            $meja = Meja::find($request->id_meja);
            $meja->update(['status' => Meja::STATUS_DIISI]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('order.index')->with('success','Pesanan berhasil dibuat');
        }

        // Single order fallback (current flow)
        $request->validate([
            'id_menu' => 'required|exists:menus,id',
            'id_meja' => 'required|exists:mejas,id',
            'id_pelanggan' => 'required|exists:pelanggans,id',
            'jumlah' => 'nullable|integer|min:1',
        ]);

        $pesanan = Pesanan::create([
            'id_menu' => $request->id_menu,
            'id_meja' => $request->id_meja,
            'id_pelanggan' => $request->id_pelanggan,
            'jumlah' => $request->filled('jumlah') ? (int) $request->jumlah : 1,
            'id_user' => Auth::user()->id,
        ]);
        
        // Update table status to occupied
        $meja = Meja::find($request->id_meja);
        $meja->update(['status' => Meja::STATUS_DIISI]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'pesanan_id' => $pesanan->id]);
        }
        return redirect()->route('order.index')->with('success','Pesanan berhasil dibuat');
    }

    public function addToExisting(Request $request)
    {
        // Log incoming request for debugging
        \Illuminate\Support\Facades\Log::info('Add to existing order request received:', [
            'all_data' => $request->all(),
            'has_orders' => $request->has('orders'),
            'orders_data' => $request->get('orders'),
            'orders_is_array' => is_array($request->get('orders'))
        ]);

        $validator = Validator::make($request->all(), [
            'id_meja' => 'required|exists:mejas,id',
            'orders' => 'required|array|min:1',
            'orders.*.id_menu' => 'required|exists:menus,id',
            'orders.*.jumlah' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            // Always return JSON for AJAX requests
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422)
                ->header('Content-Type', 'application/json');
        }

        try {
            // Get the first order to determine customer (assuming all orders for a table have the same customer)
            $existingOrder = Pesanan::where('id_meja', $request->id_meja)
                ->whereDoesntHave('transaksi')
                ->first();

            if (!$existingOrder) {
                // Always return JSON for AJAX requests
                return response()->json(['success' => false, 'message' => 'Tidak ada pesanan yang ditemukan untuk meja ini'], 404)
                    ->header('Content-Type', 'application/json');
            }

            $createdOrders = [];
            foreach ($request->orders as $index => $item) {
                \Illuminate\Support\Facades\Log::info("Adding order to existing table {$index}:", $item);
                
                $pesanan = Pesanan::create([
                    'id_menu' => $item['id_menu'],
                    'id_meja' => $request->id_meja,
                    'id_pelanggan' => $existingOrder->id_pelanggan, // Use existing customer
                    'jumlah' => isset($item['jumlah']) ? (int) $item['jumlah'] : 1,
                    'id_user' => Auth::user()->id,
                ]);
                
                $createdOrders[] = $pesanan->id;
                \Illuminate\Support\Facades\Log::info("Order added with ID: {$pesanan->id}");
            }
            
            \Illuminate\Support\Facades\Log::info('Total orders added to existing table:', $createdOrders);

            // Always return JSON for AJAX requests
            return response()->json([
                'success' => true, 
                'message' => 'Pesanan berhasil ditambahkan',
                'added_orders' => $createdOrders
            ])->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error adding orders to existing table:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Always return JSON for AJAX requests
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menambah pesanan'], 500)
                ->header('Content-Type', 'application/json');
        }
    }

}
