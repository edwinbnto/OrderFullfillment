<?php

namespace App\Http\Controllers;

use App\Helpers\OrderPriority;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PackingController extends Controller
{
    public function index()
    {
        $packingOrders = DB::table('orders')->where('status', 'PACKING')->get();

        $inPackingCount = $packingOrders->count();
        $ShippedCount   = DB::table('orders')->where('status', 'SHIPPED')->count();

        // Real count instead of hardcoded 0: total packing attempts that
        // failed because a required material was out of stock.
        $packingError = Schema::hasTable('packing_errors')
            ? DB::table('packing_errors')->count()
            : 0;

        $materials = Schema::hasTable('packing_materials')
            ? DB::table('packing_materials')->get()
            : collect();

        $lowStockMaterialCount = $materials->filter(function ($m) {
            return isset($m->stock_qty, $m->low_stock_threshold) && $m->stock_qty <= $m->low_stock_threshold;
        })->count();

        $boxMaterials = $materials->filter(fn ($m) => !empty($m->is_box));

        $packingOrdersJson = $packingOrders->mapWithKeys(function ($order) {
            $priority = OrderPriority::packing($order->created_at ?? null);
            return [
                (string) $order->id => [
                    'customer'      => $order->customer_name,
                    'item'          => $order->product_name,
                    'qty'           => $order->qty,
                    'amount'        => number_format($order->amount, 2),
                    'priority'      => $priority['label'],
                    'priorityClass' => $priority['class'],
                    'address'       => $order->address ?? '',
                ],
            ];
        });

        return view('packing', compact(
            'packingOrders',
            'inPackingCount',
            'ShippedCount',
            'packingError',
            'materials',
            'lowStockMaterialCount',
            'boxMaterials',
            'packingOrdersJson'
        ));
    }

    public function processOrder(Request $request, $id)
    {
        // 1. Validate input before doing anything else.
        $validated = $request->validate([
            'box'     => ['required', 'string'],
            'courier' => ['required', 'string'],
        ]);

        // 2. Look up the order FIRST. Fail fast if it doesn't exist,
        //    before any stock is touched.
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'error'   => 'order_not_found',
            ], 404);
        }

        // Everything below is wrapped in a top-level try/catch. If ANYTHING
        // unexpected throws here (type errors, DB errors, etc.), we still
        // want to return JSON — never let an exception fall through to
        // Laravel's HTML error page, since the frontend expects JSON.
        try {
            // Figure out which materials this shipment requires.
            $shipmentCount = DB::table('shipments')->count();
            $isBonusShipment = (($shipmentCount + 1) % 10 == 0);

            $requiredMaterials = [
                $validated['box'],
                'Foam Inserts',
                'Silica Gel Packs',
            ];

            if ($isBonusShipment) {
                $requiredMaterials = array_merge($requiredMaterials, [
                    'Packing Tape',
                    'Bubble Wrap',
                    'Fragile Tape',
                ]);
            }

            // 3. Check stock BEFORE opening any transaction. This is
            //    intentionally outside DB::transaction() — logging a packing
            //    error must never be rolled back by the same transaction
            //    that failed.
            foreach ($requiredMaterials as $materialName) {
                $row = DB::table('packing_materials')
                    ->where('name', $materialName)
                    ->first();

                if (!$row || $row->stock_qty <= 0) {
                    $this->logPackingError((string) $order->id, $materialName, $row ? 'out_of_stock' : 'material_not_found');

                    return response()->json([
                        'success'  => false,
                        'error'    => 'insufficient_stock',
                        'material' => $materialName,
                    ], 422);
                }
            }

            $result = DB::transaction(function () use ($validated, $order, $id, $requiredMaterials) {

                // Re-check with row locks inside the transaction in case stock
                // changed between the pre-check above and now (race condition).
                foreach ($requiredMaterials as $materialName) {
                    $row = DB::table('packing_materials')
                        ->where('name', $materialName)
                        ->lockForUpdate()
                        ->first();

                    if (!$row || $row->stock_qty <= 0) {
                        // Throw so the transaction rolls back cleanly; we log
                        // the error AFTER the transaction exits (see catch below).
                        throw new \RuntimeException('INSUFFICIENT_STOCK::' . $materialName);
                    }
                }

                // All materials confirmed in stock — safe to decrement.
                foreach ($requiredMaterials as $materialName) {
                    DB::table('packing_materials')
                        ->where('name', $materialName)
                        ->decrement('stock_qty', 1);
                }

                // 4. Generate a tracking number and a guaranteed-unique shipment ID.
                $trackingNumber = strtoupper($validated['courier']) . '-' . time();
                $shipmentId = $this->generateUniqueShipmentId();

                DB::table('shipments')->insert([
                    'shipment_id'     => $shipmentId,
                    'order_id'        => $order->id,
                    'customer_name'   => $order->customer_name,
                    'product_name'    => $order->product_name,
                    'qty'             => $order->qty,
                    'amount'          => $order->amount,
                    'courier'         => $validated['courier'],
                    'box_used'        => $validated['box'],
                    'tracking_number' => $trackingNumber,
                    'status'          => 'Shipped',
                    'address'         => $order->address,
                    'due_date'        => $order->due_date,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                DB::table('orders')
                    ->where('id', $id)
                    ->update(['status' => 'Shipped']);

                return [
                    'success'         => true,
                    'shipment_id'     => $shipmentId,
                    'tracking_number' => $trackingNumber,
                ];
            });
        } catch (\RuntimeException $e) {
            // Stock ran out between the pre-check and the locked re-check
            // (race condition). Transaction has already rolled back cleanly
            // at this point, so it's safe to log now.
            $materialName = str_replace('INSUFFICIENT_STOCK::', '', $e->getMessage());
            $this->logPackingError((string) $order->id, $materialName, 'out_of_stock');

            return response()->json([
                'success'  => false,
                'error'    => 'insufficient_stock',
                'material' => $materialName,
            ], 422);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'error'   => 'processing_failed',
            ], 500);
        }

        return response()->json($result, 200);
    }

    /**
     * Log a packing error. Deliberately defensive: if the packing_errors
     * table doesn't exist yet (e.g. migration not run), this must NOT
     * throw and take down the whole request — it falls back to the
     * application log instead so the real business response still reaches
     * the user.
     */
    private function logPackingError(string $orderId, string $material, string $reason): void
    {
        if (!Schema::hasTable('packing_errors')) {
            \Illuminate\Support\Facades\Log::warning('packing_errors table missing — could not log packing error', [
                'order_id' => $orderId,
                'material' => $material,
                'reason'   => $reason,
            ]);
            return;
        }

        try {
            DB::table('packing_errors')->insert([
                'order_id'   => $orderId,
                'material'   => $material,
                'reason'     => $reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Generate a shipment ID and guarantee it doesn't already exist.
     */
    private function generateUniqueShipmentId(): string
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $candidate = 'SHIP-' . strtoupper(Str::random(8));

            $exists = DB::table('shipments')
                ->where('shipment_id', $candidate)
                ->exists();

            if (!$exists) {
                return $candidate;
            }
        }

        // Extremely unlikely fallback: timestamp guarantees uniqueness.
        return 'SHIP-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
    }
}
