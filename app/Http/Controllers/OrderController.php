<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();

        $ordersToday   = Order::whereDate('created_at', today())->count();
        $inPacking     = Order::where('status', 'packing')->count();
        $shippedToday  = Order::where('status', 'shipped')
                               ->whereDate('updated_at', today())
                               ->count();

        $totalDelivered   = Order::where('status', 'delivered')->count();
        $onTimeDelivered  = Order::where('status', 'delivered')
                                  ->whereColumn('updated_at', '<=', 'due_date')
                                  ->count();
        $onTimeRate = $totalDelivered > 0
            ? round(($onTimeDelivered / $totalDelivered) * 100)
            : 0;

        return view('order', compact(
            'orders',
            'ordersToday',
            'inPacking',
            'shippedToday',
            'onTimeRate'
        ));
    }
}
