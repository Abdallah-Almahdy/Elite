<?php

namespace App\Services\Dashboard;

use App\Models\Warehouse;
use App\Models\warehouseProduct;
use App\Models\warehouseProductTransaction;
use App\Models\warehouseTransaction;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use App\Models\warehouseTransactionType;
use Illuminate\Support\Facades\Auth;

class WarehouseTransactionsService
{

    /*
    |--------------------------------------------------------------------------
    | Main methods
    |--------------------------------------------------------------------------
    */

    public function create(
        $products,
        $warehouse_id,
        $transaction_type_id,
        $new_warehouse_id

    ) {

        $this->checkTransactionType($transaction_type_id);

        switch ($transaction_type_id) {
            case warehouseTransactionType::STOCK_RECEIPT:
                $this->stockReceiptTransaction($products, $warehouse_id);
                break;
            case warehouseTransactionType::STOCK_ISSUE:
                $this->stockIssueTransaction($products, $warehouse_id);
                break;
            case warehouseTransactionType::STOCKTAKE:
                $this->stocktakeTransaction($products, $warehouse_id);
                break;
            case warehouseTransactionType::STOCK_TRANSFER:
                $this->stockTransferTransaction($products, $warehouse_id, $new_warehouse_id);
                break;
            default:
                break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | support methods
    |--------------------------------------------------------------------------
    */

    private function checkTransactionType($transaction_type_id)
    {
        if (!in_array($transaction_type_id, [
            WarehouseTransactionType::STOCKTAKE,
            WarehouseTransactionType::STOCK_ISSUE,
            WarehouseTransactionType::STOCK_RECEIPT,
            WarehouseTransactionType::STOCK_TRANSFER
        ])) {
            throw new InvalidArgumentException('نوع الحركة غير صحيح');
        }
    }

    private function logTransaction(
        string $type,
        int $warehouse_id,
        int $product_id,
        float $quantity,
        ?string $userID = null,
        ?string $notes = null
    ): void {
        warehouseTransaction::create([
            'type_id' => $type,
            'warehouse_id' => $warehouse_id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'user_id' => $userID,
            'notes' => $notes,
        ]);
    }
    private function logTransferTransaction(
        string $type,
        int $warehouse_id,
        int $targetWarehouseId,
        int $product_id,
        float $quantity,
        ?string $userID = null,
        ?string $notes = null
    ): void {
        warehouseProductTransaction::create([
            'type_id' => $type,
            'warehouse_id' => $warehouse_id,
            'target_warehouse_id' => $targetWarehouseId,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'user_id' => $userID,
            'notes' => $notes,
        ]);
    }

    private function validateProductData(array $product): void
    {
        if (!isset($product['id']) || !isset($product['quantity'])) {
            throw new \InvalidArgumentException('بيانات المنتج غير مكتملة');
        }

        if ($product['quantity'] <= 0) {
            throw new \InvalidArgumentException('الكمية يجب أن تكون أكبر من الصفر');
        }
    }
    /*
    |--------------------------------------------------------------------------
    | stock transactions handlers
    |   1. stockReceiptTransaction إضافة للمخزن
    |   2. stockIssueTransaction صرف من المخزن
    |   3. stocktakeTransaction جرد  المخزن
    |   4. stockTransferTransaction   نقل من مخزن لمخزن
    |--------------------------------------------------------------------------
    */

    /**
     * Handle stock receipt transaction (إضافة مخزنية)
     * Increases inventory for products in the specified warehouse
     */
    private function stockReceiptTransaction(array $products, int $warehouse_id): void
    {
        DB::transaction(function () use ($products, $warehouse_id) {
            foreach ($products as $product) {
                $this->validateProductData($product);

                $inventory = warehouseProduct::firstOrCreate(
                    [
                        'warehouse_id' => $warehouse_id,
                        'product_id' => $product['id']
                    ],
                    ['quantity' => 0]
                );

                $inventory->increment('quantity', $product['quantity']);
                $inventory->save();

                // Log the transaction
                $this->logTransaction(
                    warehouseTransactionType::STOCK_RECEIPT,
                    $warehouse_id,
                    $product['id'],
                    $product['quantity'],
                    Auth::user()->id,
                    $product['notes'] ?? null
                );
            }
        });
    }

    /**
     * Handle stock issue transaction (صرف مخزني)
     * Decreases inventory for products from the specified warehouse
     */
    private function stockIssueTransaction(array $products, int $warehouse_id): void
    {
        DB::transaction(function () use ($products, $warehouse_id) {
            foreach ($products as $product) {
                $this->validateProductData($product);

                $inventory = warehouseProduct::where([
                    'warehouse_id' => $warehouse_id,
                    'product_id' => $product['id']
                ])->firstOrFail();

                if ($inventory->quantity < $product['quantity']) {
                    throw new \Exception("الكمية غير متوفرة للمنتج: {$product['name']}. المتاح: {$inventory->quantity}");
                }

                $inventory->decrement('quantity', $product['quantity']);
                $inventory->save();

                // Log the transaction
                $this->logTransaction(
                    warehouseTransactionType::STOCK_ISSUE,
                    $warehouse_id,
                    $product['id'],
                    $product['quantity'],
                    Auth::user()->id,
                    $product['notes'] ?? null
                );
            }
        });
    }

    /**
     * Handle stocktake transaction (جرد مخزني)
     * Adjusts inventory to the exact counted quantity
     */
    private function stocktakeTransaction(array $products, int $warehouse_id): void
    {
        DB::transaction(function () use ($products, $warehouse_id) {
            foreach ($products as $product) {
                $this->validateProductData($product);

                $inventory = warehouseProduct::firstOrCreate(
                    [
                        'warehouse_id' => $warehouse_id,
                        'product_id' => $product['id']
                    ],
                    ['quantity' => 0]
                );

                $oldQuantity = $inventory->quantity;
                $inventory->quantity = $product['quantity'];
                $inventory->save();

                // Log the adjustment difference
                $difference = $product['quantity'] - $oldQuantity;
                $this->logTransaction(
                    warehouseTransactionType::STOCKTAKE,
                    $warehouse_id,
                    $product['id'],
                    abs($difference),
                    Auth::user()->id,
                    $product['notes'] ?? "جرد: كان {$oldQuantity}, أصبح {$product['quantity']}"
                );
            }
        });
    }

    /**
     * Handle stock transfer transaction (نقل مخزني)
     * Moves inventory from one warehouse to another
     */
    private function stockTransferTransaction(array $products, int $source_warehouse_id, int $destination_warehouse_id): void
    {
        if ($source_warehouse_id === $destination_warehouse_id) {
            throw new \Exception('لا يمكن النقل لنفس المخزن');
        }

        DB::transaction(function () use ($products, $source_warehouse_id, $destination_warehouse_id) {
            foreach ($products as $product) {
                $this->validateProductData($product);

                // Remove from source warehouse
                $sourceInventory = warehouseProduct::where([
                    'warehouse_id' => $source_warehouse_id,
                    'product_id' => $product['id']
                ])->firstOrFail();

                if ($sourceInventory->quantity < $product['quantity']) {
                    throw new \Exception("الكمية غير متوفرة للنقل في المخزن المصدر للمنتج: {$product['name']}");
                }

                $sourceInventory->decrement('quantity', $product['quantity']);
                $sourceInventory->save();

                // Add to destination warehouse
                $destinationInventory = warehouseProduct::firstOrCreate(
                    [
                        'warehouse_id' => $destination_warehouse_id,
                        'product_id' => $product['id']
                    ],
                    ['quantity' => 0]
                );

                $destinationInventory->increment('quantity', $product['quantity']);
                $destinationInventory->save();

                // Log both transactions
                $this->logTransferTransaction(
                    warehouseTransactionType::STOCK_TRANSFER,
                    $source_warehouse_id,
                    $destination_warehouse_id,
                    $product['id'],
                    $product['quantity'],
                    Auth::user()->id,
                    $product['notes'] ?? "نقل إلى المخزن {$destination_warehouse_id}"
                );

                // $this->logTransferTransaction(
                //     warehouseTransactionType::STOCK_TRANSFER,
                //     $destination_warehouse_id,
                //     $product['id'],
                //     $product['quantity'],
                //     'increase',
                //     $product['notes'] ?? "نقل من المخزن {$source_warehouse_id}"
                // );
            }
        });
    }
}
