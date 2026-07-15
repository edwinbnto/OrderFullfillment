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
            'tracking_number'

        )
        ->whereIn('status',[
            'Shipped',
            'Ready for delivery',
            'Out for delivery',
            'Delayed',
            'Delivered'
        ])
        ->get();

        $shippedToday = DB::table('orders')
        ->whereDate('updated_at', today())
        ->where('status','Shipped')
        ->count();

        $inTransit = DB::table('orders')
        ->whereDate('updated_at', today())
        ->where('status','Shipped')
        ->count();

        $delayed = DB::table('orders')
        ->whereDate('updated_at', today())
        ->where('status','Delayed')
        ->count();

        $delivered = DB::table('orders')
        ->whereDate('updated_at', today())
        ->where('status','Delivered')
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
