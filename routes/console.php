<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

Artisan::command('import-json', function () {

    $path = storage_path('app/data/data.json');

    if (!File::exists($path)) {
        $this->error('data.json not found.');
        return;
    }

    $json = json_decode(File::get($path), true);

    if (!$json || !isset($json['orders'])) {
        $this->error('Invalid JSON file.');
        return;
    }

    DB::table('orders')->truncate();

    foreach ($json['orders'] as $order) {

        DB::table('orders')->insert([
            'id' => $order['id'],
            'customer_name' => $order['customer_name'],
            'product_name' => $order['product_name'],
            'qty' => $order['qty'],
            'status' => strtoupper($order['status']),
            'due_date' => $order['due_date'],
            'created_at' => $order['created_at'],
            'updated_at' => $order['updated_at'],
        ]);
    }

    $this->info('Orders imported successfully!');
});