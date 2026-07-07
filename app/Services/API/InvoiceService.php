<?php

namespace App\Services\API;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Exception;
use Illuminate\Support\Facades\DB;

class InvoiceService
{


    public function create($data)
    {
        DB::beginTransaction();

        try {

            $shift = app(ShiftService::class)->openShift(auth()->user()->id);

            $warehouse = Warehouse::find($data['warehouse_id']);


            $invoice = $this->createInvoice($data, $shift);

            $this->createPayments($invoice, $data['payment_methods']);

            $result = $this->processProducts($data['products'], $invoice);

            $this->decrementWarehouse($warehouse,$result['warehouseUpdates']);

            $this->storeInvoiceProducts($result['invoiceProducts']);

            $this->calculateTotal($invoice,$result['total']);

            DB::commit();

            return new InvoiceResource($invoice->fresh());
        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    protected function processProducts(array $products,$invoice): array
    {
        $productIds = collect($products)->pluck('id')->unique();

        $loadedProducts = Product::with([
            'units.productUnits.components.product.units'
        ])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $invoiceProducts = [];
        $warehouseUpdates = [];
        $total = 0;

        foreach ($products as $item) {

            $product = $loadedProducts->get($item['id']);

            if (!$product) {
                throw new Exception("Product {$item['id']} not found.");
            }


            $unit = $product->units->first(function ($unit) use ($item) {
                return (float) $unit->pivot->conversion_factor === (float) $item['unit_conversion_factor'];
            });


            if (!$unit) {
                throw new Exception("Conversion factor not exists.");
            }

            $price = $unit->pivot->sallprice;


            if ($product->is_stock) {

                if (!$product->uses_recipe) {

                    $warehouseUpdates[$product->id] =
                        ($warehouseUpdates[$product->id] ?? 0)
                        + ($item['quantity'] * $item['unit_conversion_factor']);
                } else {

                    $productUnit = $product->units
                        ->firstWhere('id', $item['unit_id']);

                    if (!$productUnit) {
                        throw new Exception("Unit not found.");
                    }

                    foreach ($productUnit->pivot->components as $component) {

                        $componentProduct = $component->product;

                        $componentUnit = $componentProduct->units
                            ->find($component->component_unit_id);

                        $factor = $componentUnit
                            ? $componentUnit->pivot->conversion_factor
                            : 1;

                        $warehouseUpdates[$componentProduct->id] =
                            ($warehouseUpdates[$componentProduct->id] ?? 0)
                            + (
                                $item['quantity']
                                * $component->quantity
                                * $factor
                            );
                    }
                }
            }

            $subtotal = $price
                * $item['unit_conversion_factor']
                * $item['quantity'];

            $total += $subtotal;

            $invoiceProducts[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_conversion_factor' => $item['unit_conversion_factor'],
                'price' => $price * $item['unit_conversion_factor'],
                'subtotal' => $subtotal,
                'invoice_id' => $invoice->id
            ];
        }

        return [
            'invoiceProducts' => $invoiceProducts,
            'warehouseUpdates' => $warehouseUpdates,
            'total' => $total,
        ];
    }
    protected function decrementWarehouse(Warehouse $warehouse,array $warehouseUpdates): void {

        $products = WarehouseProduct::where('warehouse_id', $warehouse->id)
            ->whereIn('product_id', array_keys($warehouseUpdates))
            ->lockForUpdate()
            ->get()
            ->keyBy('product_id');

        foreach ($warehouseUpdates as $productId => $qty) {

            $warehouseProduct = $products[$productId];
            // عايز نشوف البشمهندس عايز يعمل اي في الحته دي

            if ($warehouseProduct->quantity < $qty) {
                throw new Exception("Insufficient stock");
            }

            $warehouseProduct->decrement('quantity', $qty);
        }
    }
    protected function createInvoice($data, $shift): Invoice
    {

        return Invoice::create([
            'address'    => $data['address'] ?? null,
            'cashier_id' => 1,
            'shift_id'   => $shift->id,
            'safe_id'    => $shift->safe_id,
            'total'      => 0,
            "warehouse_id" => $data['warehouse_id']
        ]);
    }

    protected function createPayments(Invoice $invoice, array $payments): void
    {
        $invoice->payments()->createMany($payments);
    }

    protected function storeInvoiceProducts(array $invoiceProducts): void
    {
        InvoiceProduct::insert($invoiceProducts);
    }

    protected function calculateTotal(Invoice $invoice, float $total): void
    {

        $invoice->update([
            'total' => $total
        ]);
    }
}
