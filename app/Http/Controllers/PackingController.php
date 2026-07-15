<?php

namespace App\Http\Controllers;

use App\Helpers\OrderPriority;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PackingController extends Controller
{
    public function index()
    {
        // ---- Orders currently in the PACKING column ----
        $packingOrders = DB::table('orders')->where('status', 'PACKING')->get();

        // ---- Stats row (all derived from the orders table, nothing hardcoded) ----
        $inPackingCount    = $packingOrders->count();
        $readyToShipCount  = DB::table('orders')->where('status', 'READY_TO_SHIP')->count();
        $packingErrorToday = 0; // TODO: wire to a packing_errors log once that table exists

        // ---- Packing materials (boxes, tape, wrap, etc.) ----
        $materials = Schema::hasTable('packing_materials')
            ? DB::table('packing_materials')->get()
            : collect();

        $lowStockMaterialCount = $materials->filter(function ($m) {
            return isset($m->stock_qty, $m->low_stock_threshold) && $m->stock_qty <= $m->low_stock_threshold;
        })->count();

        // Box options shown inside the "prepare shipment" modal.
        $boxMaterials = $materials->filter(fn ($m) => !empty($m->is_box));

        // Lookup (order id => details) for the modal, in the shape the front-end JS expects.
        $packingOrdersJson = $packingOrders->mapWithKeys(function ($order) {
            $priority = OrderPriority::packing($order->created_at ?? null);
            return [
                (string) $order->id => [
                    'customer'      => $order->customer_name,
                    'item'          => $order->product_name,
                    'qty'           => $order->qty,
                    'priority'      => $priority['label'],
                    'priorityClass' => $priority['class'],
                    'address'       => $order->address ?? '',
                ],
            ];
        });

        return view('packing', compact(
            'packingOrders',
            'inPackingCount',
            'readyToShipCount',
            'packingErrorToday',
            'materials',
            'lowStockMaterialCount',
            'boxMaterials',
            'packingOrdersJson'
        ));
    }

    public function processOrder(Request $request, $id)
{
    
    $order = DB::table('orders')
        ->where('id', $id)
        ->first();

    DB::table('packing_materials')
    ->where('name', $request->box)
    ->decrement('stock_qty', 1);

    DB::table('packing_materials')
    ->where('name', 'Foam Inserts')
    ->decrement('stock_qty', 1);

    DB::table('packing_materials')
    ->where('name', 'Silica Gel Pack')
    ->decrement('stock_qty', 1);

    $shipmentCount = DB::table('shipments')->count();

    if (($shipmentCount + 1) % 10 == 0) {
    DB::table('packing_materials')
        ->where('name', 'Packing Tape')
        ->decrement('stock_qty', 1);

    DB::table('packing_materials')
        ->where('name', 'Bubble Wrap')
        ->decrement('stock_qty', 1);

    DB::table('packing_materials')
        ->where('name', 'Fragile Tape')
        ->decrement('stock_qty', 1);
}


    if (!$order) {
        return response()->json([
            'success' => false
        ]);
    }

    $trackingNumber =
        strtoupper($request->courier)
        . '-'
        . time();

    $shipmentId =
        'SHIP-' . str_pad(
            rand(1,999999),
            6,
            '0',
            STR_PAD_LEFT
        );

    DB::table('shipments')->insert([
        'shipment_id' => $shipmentId,
        'order_id' => $order->id,

        'customer_name' => $order->customer_name,
        'product_name' => $order->product_name,
        'qty' => $order->qty,

        'courier' => $request->courier,
        'box_used' => $request->box,

        'tracking_number' => $trackingNumber,

        'status' => 'Shipped',

        'address' => $order->address,

        'created_at' => now(),
        'updated_at' => now()
    ]);

    DB::table('orders')
        ->where('id', $id)
        ->update([
            'status' => 'Shipped'
        ]);

    return response()->json([
        'success' => true
    ]);
}
}
