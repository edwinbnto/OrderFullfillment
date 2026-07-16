<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Single order detail page. Moved here from the abstract base
     * Controller, where it didn't belong (every controller inherited
     * an order-specific method) and was broken anyway (Order class
     * was never imported there).
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Orders page (order.blade.php)
     */
    public function index()
    {
        $orders       = DB::table('orders')->orderByDesc('created_at')->get();
        $ordersToday  = DB::table('orders')->where('status', 'NEW')->count();
        $inPacking    = DB::table('orders')->where('status', 'PACKING')->count();
        $shippedToday = DB::table('orders')->where('status', 'SHIPPED')->count();
        $delivered    = DB::table('orders')->where('status', 'DELIVERED')->count();
        $total        = DB::table('orders')->count();
        $onTimeRate   = $total > 0 ? round(($delivered / $total) * 100) . '%' : '0%';


        $recentActivity = DB::table('orders')
            ->whereIn('status', ['PACKING', 'SHIPPED', 'CANCELLED'])
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get()
            ->map(function ($o) {
                $status = strtoupper($o->status);

                if ($status === 'SHIPPED') {
                    $o->activity_icon    = '🚚';
                    $o->activity_message = "Order {$o->id} has been shipped";
                } elseif ($status === 'CANCELLED') {
                    $o->activity_icon    = '❌';
                    $o->activity_message = "Order {$o->id} has been cancelled";
                } else {
                    $o->activity_icon    = '📦';
                    $o->activity_message = "Order {$o->id} moved to packing";
                }

                return $o;
            });

        return view('order', compact(
            'orders', 'ordersToday', 'inPacking', 'shippedToday', 'onTimeRate', 'recentActivity'
        ));
    }

    /**
     * Packing page (packing.blade.php)
     */
    public function packing()
    {
        $packingOrders    = DB::table('orders')->where('status', 'PACKING')->get();
        $readyToShipCount = DB::table('orders')->where('status', 'READY_TO_SHIP')->count();
        $packingErrorToday = 0; // TODO: hook up to a packing_errors table once one exists

        // Expected table: packing_materials(id, name, icon, stock_qty,
        // low_stock_threshold, stock_label, is_box, box_size)
        $materials = DB::table('packing_materials')->get();

        return view('packing', compact(
            'packingOrders', 'readyToShipCount', 'packingErrorToday', 'materials'
        ));
    }

    /**
     * AJAX: mark an order as being prepared -> moves it to PACKING.
     * The order row itself is never deleted, only its status changes,
     * so it keeps showing on the Orders page while also now appearing
     * in the Packing column/queue on the dashboard and packing page.
     */
    public function prepare($id): JsonResponse
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if (strtoupper($order->status) !== 'NEW') {
            return response()->json([
                'success' => false,
                'message' => 'Order is already ' . strtoupper($order->status) . '.',
            ], 409);
        }

        DB::table('orders')->where('id', $id)->update([
            'status'     => 'PACKING',
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'status'  => 'PACKING',
        ]);
    }

    /**
     * AJAX: cancel an order.
     * Order row is never deleted, only its status changes to CANCELLED,
     * so it drops its priority badge / Prepare button on the Orders page
     * and shows up in the Recent activity / dashboard activity feed.
     */
    public function cancel($id): JsonResponse
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        $status = strtoupper($order->status);

        if ($status === 'CANCELLED') {
            return response()->json([
                'success' => false,
                'message' => 'Order is already cancelled.',
            ], 409);
        }

        if (in_array($status, ['SHIPPED', 'DELIVERED'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order has already been ' . strtolower($status) . ' and can no longer be cancelled.',
            ], 409);
        }

        DB::table('orders')->where('id', $id)->update([
            'status'     => 'CANCELLED',
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'status'  => 'CANCELLED',
        ]);
    }
}