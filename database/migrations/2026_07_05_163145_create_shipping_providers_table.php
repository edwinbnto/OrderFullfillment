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
    Schema::create('shipping_providers', function (Blueprint $table) {
        $table->string('id', 20)->primary();
        $table->string('name', 100)->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('shipping_providers');
}
};
