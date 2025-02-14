<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    // GET: Ambil semua data order
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders, 200);
    }

    // POST: Simpan data order baru
    public function store(Request $request)
    {
        // Validasi hanya untuk kolom yang ada di model
        $request->validate([
            'transaction_time' => 'required|date',
            'total_price' => 'required|numeric',
            'total_item' => 'required|integer',
            'payment_amount' => 'required|numeric',
            'cashier_id' => 'required|integer',
            'cashier_name' => 'required|string',
            'payment_method' => 'required|string'
        ]);

        // Buat data order berdasarkan hanya kolom yang ada di model
        $order = Order::create([
            'transaction_time' => $request->transaction_time,
            'total_price' => $request->total_price,
            'total_item' => $request->total_item,
            'payment_amount' => $request->payment_amount,
            'cashier_id' => $request->cashier_id,
            'cashier_name' => $request->cashier_name,
            'payment_method' => $request->payment_method,
        ]);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ], 201);
    }

    // GET: Ambil satu data order berdasarkan ID
    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json($order, 200);
    }

    // PUT/PATCH: Update data order berdasarkan ID
    public function update(Request $request, $id)
{
    $order = Order::find($id);
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // Validasi hanya untuk kolom yang ada di model
    $request->validate([
        'transaction_time' => 'sometimes|date',
        'total_price' => 'sometimes|numeric',
        'total_item' => 'sometimes|integer',
        'payment_amount' => 'sometimes|numeric',
        'cashier_id' => 'sometimes|integer',
        'cashier_name' => 'sometimes|string',
        'payment_method' => 'sometimes|string'
    ]);

    // Update hanya kolom yang ada di model
    $order->update($request->only([
        'transaction_time',
        'total_price',
        'total_item',
        'payment_amount',
        'cashier_id',
        'cashier_name',
        'payment_method',
    ]));

    return response()->json(['message' => 'Order updated successfully', 'order' => $order], 200);
}

// DELETE: Hapus data order berdasarkan ID
public function destroy($id)
{
    $order = Order::find($id);
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    $order->delete();
    return response()->json(['message' => 'Order deleted successfully'], 200);
}

}
