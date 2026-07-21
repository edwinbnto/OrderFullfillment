<?php

namespace App\Http\Controllers;

use App\Models\ReturnItem;

class ReturnController extends Controller
{
public function index()
{
    $returns = ReturnItem::all();

    $pendingReturns = ReturnItem::where('status', 'NEW')->count();

    $refundedToday = ReturnItem::whereDate(
        'updated_at',
        today()
    )->where('status', 'Refunded')
     ->count();

    return view('return', compact(
        'returns',
        'pendingReturns',
        'refundedToday'
    ));
}
}