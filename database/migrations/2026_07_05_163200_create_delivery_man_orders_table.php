<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('delivery_man_orders', function (Blueprint $table) {
        $table->id();
        $table->string('delivery_man_id', 20)->nullable();
        $table->string('order_id', 20)->nullable();

        $table->foreign('delivery_man_id')
              ->references('id')->on('delivery_men')
              ->nullOnDelete();

        $table->foreign('order_id')
              ->references('id')->on('orders')
              ->nullOnDelete();
    });
}

public function down()
{
    Schema::dropIfExists('delivery_man_orders');
}
};
