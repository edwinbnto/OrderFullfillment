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
    Schema::create('delivery_men', function (Blueprint $table) {
        $table->string('id', 20)->primary();
        $table->integer('age')->nullable();
        $table->string('license_num', 30)->nullable();
        $table->string('vehicle_type', 50)->nullable();
        $table->string('shipping_provider_id', 20)->nullable();

        $table->foreign('shipping_provider_id')
              ->references('id')->on('shipping_providers')
              ->nullOnDelete();
    });
}

public function down()
{
    Schema::dropIfExists('delivery_men');
}
};
