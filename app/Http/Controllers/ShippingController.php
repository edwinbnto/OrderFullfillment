<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Shipment;

class ShippingController extends Controller
{
    public function index()
    {
        $shipments = Shipment::select(
            'shipment_id',
            'customer_name',
            'product_name',
            'status',
            'due_date',
            'address',
            'tracking_number',
            'courier',
            'amount'
        )
        ->whereIn('status',[
            'SHIPPED',
            'READY_TO_SHIP',
            'OUT_FOR_DELIVERY',
            'DELAYED',
            'DELIVERED'
        ])
        ->get();

        $shippedToday = Order::whereDate('updated_at', today())
        ->where('status','SHIPPED')
        ->count();

        // Was previously an exact copy of $shippedToday (same status filter).
        // In-transit orders are the ones out for delivery, not just shipped.
        $inTransit = Order::whereDate('updated_at', today())
        ->where('status','OUT_FOR_DELIVERY')
        ->count();

        $delayed = Order::whereDate('updated_at', today())
        ->where('status','DELAYED')
        ->count();

        $delivered = Order::whereDate('updated_at', today())
        ->where('status','DELIVERED')
        ->count();

        $onTimeRate = $delivered
        ? round(($delivered / ($delivered + $delayed))*100)
        : 0;

        return view('shipping',compact(
            'shipments',
            'shippedToday',
            'inTransit',
            'delayed',
            'delivered',
            'onTimeRate'
        ));
    }
}