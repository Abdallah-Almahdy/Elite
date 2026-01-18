<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
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

            $product = Product::find($InvoiceProduct['id']);
            $price = $product->units()
                ->where('conversion_factor', $InvoiceProduct['unit_conversion_factor'])
                ->value('sallPrice');


            $WarehouseProduct = WarehouseProduct::where('warehouse_id', $warehouse->id)
                ->where('product_id',$product->id)->first();

            if ($WarehouseProduct->quantity == 0 || $WarehouseProduct->quantity < $InvoiceProduct['quantity']) {
                $WarehouseProduct->quantity = 0;
                $WarehouseProduct->save();
            } else {
                $WarehouseProduct->decrement('quantity', $InvoiceProduct['quantity']);
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

    public function destroy() {}
}
