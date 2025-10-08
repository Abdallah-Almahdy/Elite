<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\productsResource;
use App\Models\Product;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Validate;

class PosController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | IOC
    |--------------------------------------------------------------------------
    */
    use ApiTrait;


    /*
    |--------------------------------------------------------------------------
    | Main methods
    |--------------------------------------------------------------------------
    */
    public function getProductByBarcode($barcode)
    {
        $this->checkExists(Product::class, 'bar_code', $barcode);

        $product = Product::where('bar_code', $barcode)->first();

        return $this->success($product);
    }

    public function getProductsByName($name)
    {

        $products = Product::where('name', 'like', '%' . $name . '%')->get();

        if ($products->isEmpty()) {
            return [];
        }

        return $this->successCollection(productsResource::class, $products);
    }

    public function makeInvoice(Request $request)
    {

        // try {
        return DB::transaction(function () use ($request) {
            $request->validate([
                'cashier_id' => 'required|exists:users,id',
                'safe_id' => 'required|exists:safes,id',
                'total' => 'required|numeric|min:0',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price' => 'required|numeric|min:0',
                'products.*.subtotal' => 'required|numeric|min:0',
            ]);


            // Create the invoice
            $invoice = Invoice::create([
                'cashier_id' => $request->cashier_id,
                'safe_id' => $request->safe_id,
                'total' => $request->total,
            ]);

            // Create invoice products
            $invoiceProducts = [];
            foreach ($request->products as $productData) {
                $invoiceProduct = InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price'],
                    'subtotal' => $productData['subtotal'],
                ]);

                // Update product stock (if you have stock management)
                $product = Product::find($productData['product_id']);
                if ($product && isset($product->qnt)) {
                    $product->decrement('qnt', $productData['quantity']);
                }

                $invoiceProducts[] = $invoiceProduct;
            }
            return $this->success(
                [
                    'invoice' => $invoice->load('products', 'cashier', 'safe'),
                    'products' => $invoiceProducts
                ],
                'Invoice created successfully'
            );
        });
        // } catch (\Exception $e) {
        //     return $this->Apiresponse($e->getMessage(), 'Failed to create invoice', 500);
        // }
    }
    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    private function checkScaleProduct($barcode)
    {

        $scaleProductBarcode = (string)substr($barcode, 1, 6);

        $this->checkExists(product::class, 'bar_code', $scaleProductBarcode);

        $scaleProductData = product::where('bar_code', $scaleProductBarcode)->first();

        return $scaleProductData;
    }
}
