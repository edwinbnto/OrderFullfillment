<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function show($id) {
    $order = Order::findOrFail($id);
    return view('orders.show', compact('order'));
}

}
