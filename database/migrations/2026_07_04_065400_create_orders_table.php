<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->string('id')->primary();

        $table->string('customer_name');
        $table->string('product_name');

        $table->integer('qty')->default(1);

        $table->string('status')->default('NEW');

        $table->date('due_date');

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('orders');
}
};
