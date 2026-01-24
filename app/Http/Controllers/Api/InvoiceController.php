<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\ProductUnits;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function index()
    {

        return  InvoiceResource::collection(Invoice::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'payment_method' => 'required',


            'products' => 'required|array',


            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.unit_conversion_factor' => 'required|numeric|min:0.0001',
            'products.*.quantity' => 'required|numeric|min:1',
        ]);

        $invoice = Invoice::create([
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'cashier_id' => 1,
            'total' => 0,
            'safe_id' => null


        ]);
        $total = 0;
        $warehouse  = Warehouse::where('is_default', true)->first();


        foreach ($request->products as $InvoiceProduct) {

            $product = Product::with(['units', 'units.productUnits.components'])->find($InvoiceProduct['id']);


            $price = $product->units()
                ->where('conversion_factor', $InvoiceProduct['unit_conversion_factor'])
                ->value('sallPrice');

                if(!$price){
                    return response()->json([
                        'message' => "conversion factor not exists"
                    ]);
                }

                
            if ($product->is_stock) {
                if (!$product->uses_recipe) {
                    $this->decreamentwarehouse($product, $warehouse, $InvoiceProduct, false);
                } else {

                    $productUnit = $product->units->where('id', $InvoiceProduct['unit_id'])->first();
                    if(!$productUnit){
                        return response()->json([
                            'message' => "unit_id  not exists"
                        ]);
                    }
                    $productUnit->pivot->refresh();

                    foreach ($productUnit->pivot->components as $component) {
                        $comProduct = $component->product;
                        $this->decreamentwarehouse($comProduct, $warehouse, $InvoiceProduct, $component);
                    }
                }
            }

            $subtotal =  $price * $InvoiceProduct['unit_conversion_factor'] * $InvoiceProduct['quantity'];
            $total = $total + $subtotal;

            InvoiceProduct::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'quantity' => $InvoiceProduct['quantity'],
                'unit_conversion_factor' => $InvoiceProduct['unit_conversion_factor'],
                'price' => $price * $InvoiceProduct['unit_conversion_factor'],
                'subtotal' => $subtotal
            ]);
        }


        $invoice->total = $total;
        $invoice->save();
        return new InvoiceResource($invoice);
    }


    public function decreamentwarehouse($product, $warehouse, $InvoiceProduct, $component)
    {




        $WarehouseProduct = WarehouseProduct::where('warehouse_id', $warehouse->id)
            ->where('product_id', $product->id)->first();

        if ($WarehouseProduct->quantity == 0 || $WarehouseProduct->quantity < $InvoiceProduct['quantity']) {
            $WarehouseProduct->quantity = 0;
            $WarehouseProduct->save();
        } elseif ($component) {
            $productUnit = $product->units->where('id',  $component->component_unit_id)->first();
            $WarehouseProduct->decrement('quantity', $InvoiceProduct['quantity'] * (($component->quantity * $productUnit->pivot->conversion_factor) ?? 1));
            $WarehouseProduct->save();
        } else {
            $WarehouseProduct->decrement('quantity', $InvoiceProduct['quantity']  * $InvoiceProduct['unit_conversion_factor']);
            $WarehouseProduct->save();
        }
    }
    public function destroy() {}
}
