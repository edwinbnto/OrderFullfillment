<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingController extends Controller
{
    public function index()
    {
        $shipments = DB::table('shipments')
        ->select(
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

        $shippedToday = DB::table('orders')
        ->whereDate('updated_at', today())
        ->where('status','SHIPPED')
        ->count();

        // Was previously an exact copy of $shippedToday (same status filter).
        // In-transit orders are the ones out for delivery, not just shipped.
        $inTransit = DB::table('orders')
        ->whereDate('updated_at', today())
        ->where('status','OUT_FOR_DELIVERY')
        ->count();

        $delayed = DB::table('orders')
        ->whereDate('updated_at', today())
        ->where('status','DELAYED')
        ->count();

        $delivered = DB::table('orders')
        ->whereDate('updated_at', today())
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