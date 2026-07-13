<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE SEQUENCE IF NOT EXISTS order_id_seq START 5201;

            CREATE OR REPLACE FUNCTION generate_order_id()
            RETURNS TRIGGER AS $$
            BEGIN
                IF NEW.id IS NULL OR NEW.id = '' THEN
                    NEW.id := '#ORD-' || nextval('order_id_seq');
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            DROP TRIGGER IF EXISTS set_order_id ON orders;
            CREATE TRIGGER set_order_id
            BEFORE INSERT ON orders
            FOR EACH ROW
            EXECUTE FUNCTION generate_order_id();
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS set_order_id ON orders;
            DROP FUNCTION IF EXISTS generate_order_id();
            DROP SEQUENCE IF EXISTS order_id_seq;
        ");
    }
};
