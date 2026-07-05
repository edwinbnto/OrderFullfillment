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
    Schema::create('packing_materials', function (Blueprint $table) {
        $table->string('id', 20)->primary();
        $table->string('name', 100)->nullable();
        $table->integer('quantity')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('packing_materials');
}
};
