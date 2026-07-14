<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ---- Stats row ----
        $ordersReceivedToday = DB::table('orders')->where('status', 'NEW')->count();
        $inPackingCount      = DB::table('orders')->where('status', 'PACKING')->count();
        $shippedTodayCount   = DB::table('orders')->where('status', 'SHIPPED')->count();
        $deliveredCount      = DB::table('orders')->where('status', 'DELIVERED')->count();
        $totalOrders         = DB::table('orders')->count();
        $onTimeRate          = $totalOrders > 0 ? round(($deliveredCount / $totalOrders) * 100) : 0;

        // ---- Board columns ----
        $newOrders       = DB::table('orders')->where('status', 'NEW')->get();
        $packingOrders   = DB::table('orders')->where('status', 'PACKING')->get();
        $shippedOrders   = DB::table('orders')->where('status', 'SHIPPED')->get();
        $cancelledOrders = DB::table('orders')->where('status', 'CANCELLED')->get();

        // ---- Sidebar ----.
        $alerts = $newOrders;

        // Activity feed: packing + shipped + cancelled orders together, newest first.
        $activity = $packingOrders
            ->map(function ($order) {
                $order->activity_icon    = '📦';
                $order->activity_message = "Order {$order->id} moved to packing";
                $order->activity_time    = $order->updated_at ?? $order->created_at ?? null;
                return $order;
            })
            ->concat($shippedOrders->map(function ($order) {
                $order->activity_icon    = '🚚';
                $order->activity_message = "Order {$order->id} has been shipped";
                $order->activity_time    = $order->updated_at ?? $order->created_at ?? null;
                return $order;
            }))
            ->concat($cancelledOrders->map(function ($order) {
                $order->activity_icon    = '❌';
                $order->activity_message = "Order {$order->id} has been cancelled";
                $order->activity_time    = $order->updated_at ?? $order->created_at ?? null;
                return $order;
            }))
            ->sortByDesc('activity_time')
            ->values();

        return view('dashboard', compact(
            'ordersReceivedToday',
            'inPackingCount',
            'shippedTodayCount',
            'deliveredCount',
            'totalOrders',
            'onTimeRate',
            'newOrders',
            'packingOrders',
            'shippedOrders',
            'cancelledOrders',
            'alerts',
            'activity'
        ));
    }
}
