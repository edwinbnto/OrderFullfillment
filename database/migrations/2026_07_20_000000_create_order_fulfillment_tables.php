<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ---------------------------------------------------------
        // delivery_men
        // ---------------------------------------------------------
        Schema::create('delivery_men', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('name', 100);
            $table->smallInteger('age')->nullable();
            $table->string('license_num', 30)->nullable();
            $table->string('plate_number', 20)->nullable();
            $table->string('vehicle_type', 50)->nullable();
            $table->string('courier_provider', 20)->nullable();
            $table->string('status', 20)->default('AVAILABLE');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        // ---------------------------------------------------------
        // orders (id = VARCHAR primary key, auto-generated as ORD-001, ORD-002, ...)
        // ---------------------------------------------------------
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('customer_name', 255);
            $table->string('status', 255)->default('NEW');
            $table->date('due_date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('address', 255)->nullable();
            $table->string('product_name', 255)->default('N/A');
            $table->integer('qty')->default(1);
            $table->decimal('product_amount', 10, 2)->default(0);
        });

        // Sequence backing the ORD-XXX numbering
        DB::unprepared('CREATE SEQUENCE IF NOT EXISTS orders_id_seq START 1;');

        // Trigger function: fills id with ORD-001 style value if not supplied
        DB::unprepared("
            CREATE OR REPLACE FUNCTION generate_order_id()
            RETURNS TRIGGER AS $$
            BEGIN
                IF NEW.id IS NULL OR NEW.id = '' THEN
                    NEW.id := 'ORD-' || LPAD(nextval('orders_id_seq')::text, 3, '0');
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER trg_generate_order_id
            BEFORE INSERT ON orders
            FOR EACH ROW
            EXECUTE FUNCTION generate_order_id();
        ");

        // ---------------------------------------------------------
        // order_items (FK -> orders.id, cascade on delete)
        // ---------------------------------------------------------
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id', 255);
            $table->string('product_name', 255);
            $table->integer('qty')->default(1);
            $table->decimal('product_amount', 10, 2)->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('order_id', 'order_items_order_id_fkey')
                  ->references('id')->on('orders')
                  ->onDelete('cascade');

            $table->index('order_id', 'idx_order_items_order_id');
        });

        // ---------------------------------------------------------
        // packing_errors
        // ---------------------------------------------------------
        Schema::create('packing_errors', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 50);
            $table->string('material', 255);
            $table->string('reason', 50);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->index('created_at', 'idx_packing_errors_created_at');
            $table->index('order_id', 'idx_packing_errors_order_id');
        });

        // ---------------------------------------------------------
        // requisitions
        // ---------------------------------------------------------
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('req_number', 255)->unique();
            $table->string('item', 255);
            $table->integer('qty')->default(1);
            $table->string('department', 255)->nullable();
            $table->string('requested_by', 255)->nullable();
            $table->date('date_requested');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('priority', 100)->nullable();
            $table->string('categories', 255)->default('Packing Materials');
        });

        // ---------------------------------------------------------
        // shipments
        // ---------------------------------------------------------
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_id', 50)->unique();
            $table->string('order_id', 50);
            $table->string('customer_name', 255);
            $table->string('product_name', 255);
            $table->integer('qty');
            $table->string('courier', 100);
            $table->string('box_used', 100);
            $table->string('tracking_number', 100)->unique();
            $table->string('status', 50)->default('Ready for delivery');
            $table->text('address')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->date('due_date')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('delivery_man_id', 20)->nullable();
            $table->timestamp('shipped_at')->nullable();
        });

        // ---------------------------------------------------------
        // returns (id also VARCHAR primary key)
        // ---------------------------------------------------------
        Schema::create('returns', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('order_id', 255);
            $table->string('customer_name', 255);
            $table->string('product_name', 255);
            $table->string('reason', 255);
            $table->string('status', 255)->default('NEW');
            $table->string('resolution', 255)->nullable();
            $table->date('due_date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('address', 255)->nullable();
            $table->decimal('refund_amount', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('requisitions');
        Schema::dropIfExists('packing_errors');
        Schema::dropIfExists('order_items');

        DB::unprepared('DROP TRIGGER IF EXISTS trg_generate_order_id ON orders;');
        DB::unprepared('DROP FUNCTION IF EXISTS generate_order_id();');
        DB::unprepared('DROP SEQUENCE IF EXISTS orders_id_seq;');

        Schema::dropIfExists('orders');
        Schema::dropIfExists('delivery_men');
    }
};
