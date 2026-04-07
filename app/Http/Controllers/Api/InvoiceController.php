<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\ProductUnits;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Services\API\ShiftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{

    public function index()
    {

        return  InvoiceResource::collection(Invoice::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'string',
            'payment_methods.*.key' => 'string|in:cash,credit_card,instapay,wallet,remaining',
            'payment_methods.*.amount' => 'required_with:payment_methods|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.unit_conversion_factor' => 'required|numeric|min:0.0001',
            'products.*.quantity' => 'required|numeric|min:1',

        ]);



        try {
            DB::beginTransaction();
            $ShiftService = new ShiftService();
            $shift =  $ShiftService->openShift(1);


            $invoice = Invoice::create([
                'address' => $request->address,
                'cashier_id' => 1,
                'shift_id' => $shift->id,
                'total' => 0,
                'safe_id' => $shift->safe_id
            ]);


            foreach ($request->payment_methods as $payment) {
                $invoice->payments()->create([
                    'payment_method' => $payment['key'],
                    'amount' => $payment['amount'],

                ]);
            }


            $total = 0;
            $warehouse  = Warehouse::where('is_default', true)->first();


            foreach ($request->products as $InvoiceProduct) {

                $product = Product::with(['units', 'units.productUnits.components'])->find($InvoiceProduct['id']);

                $price = $product->units()
                    ->where('conversion_factor', $InvoiceProduct['unit_conversion_factor'])
                    ->value('sallPrice');

                if (!$price) {
                    return response()->json([
                        'message' => "conversion factor not exists"
                    ]);
                }

                if ($product->is_stock) {
                    if (!$product->uses_recipe) {
                        $this->decreamentwarehouse($product, $warehouse, $InvoiceProduct, false);
                    } else {
                        // if the product uses recipe every product unit must have components and every product has components
                        // every component has product
                        $productUnit = $product->units->where('id', $InvoiceProduct['unit_id'])->first();

                        if (!$productUnit) {
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
            DB::commit();
            return new InvoiceResource($invoice);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while creating the invoice.',
                'error' => $e->getMessage()
            ], 500);
        }
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


    public function inviceConfig()
    {

        $config = User::find(1)->inviceConfig;
        if (!$config) {
            return response()->json([
                'message' => 'No config found for this user.'
            ], 404);
        }

        return response()->json([
            'config' => $config,
            'mainWarehouse' => Warehouse::where('is_default', true)->first()->name
        ]);
    }

    public function editInviceConfig(Request $request)
    {
        $request->validate([
            'printerName' => 'string|nullable',
            'password' => 'string|nullable',
            'taxValue' => 'numeric|nullable',
            'defaultPaymentMethod' => 'string|in:cash,credit_card,instapay,wallet,remaining|nullable',
            'defaultInvoiceType' => 'string|in:take_away,hall,delvery|nullable',
            'applyTax' => 'boolean|nullable',
            'taxTypes' => 'string|in:%,pound|nullable',
            'user_id' => 'required|integer|exists:users,id',
            'allowedPaymentMethods' => 'array|nullable',
            'allowedPaymentMethods.*' => 'string|in:cash,credit_card,instapay,wallet,remaining',
            'allowedInvoiceTypes' => 'array|nullable',
            'allowedInvoiceTypes.*' => 'string|in:take_away,hall,delvery',

        ]);


        $config = User::find($request->user_id)->inviceConfig;
        $allowedPaymentMethods = collect($request->allowedPaymentMethods)
            ->push($request->defaultPaymentMethod)
            ->unique()
            ->values()
            ->toArray();

        $allowedInvoiceTypes = collect($request->allowedInvoiceTypes)
            ->push($request->defaultInvoiceType)
            ->unique()
            ->values()
            ->toArray();

        if (!$config) {
            $config = User::find($request->user_id)->inviceConfig()->create([
                'printerName' => $request->printerName ?? $config->printerName,
                'password' => $request->password ?? $config->password,
                'taxValue' => $request->taxValue ?? $config->taxValue,
                'defaultPaymentMethod' => $request->defaultPaymentMethod ?? $config->defaultPaymentMethod,
                'defaultInvoiceType' => $request->defaultInvoiceType ?? $config->defaultInvoiceType,
                'applyTax' => $request->applyTax ?? $config->applyTax,
                'taxTypes' => $request->taxTypes ?? $config->taxTypes,
                'allowedPaymentMethods' => $allowedPaymentMethods,
                'allowedInvoiceTypes' => $allowedInvoiceTypes
            ]);
        } else {

            $config->update([
                'printerName' => $request->printerName ?? $config->printerName,
                'password' => $request->password ?? $config->password,
                'taxValue' => $request->taxValue ?? $config->taxValue,
                'defaultPaymentMethod' => $request->defaultPaymentMethod ?? $config->defaultPaymentMethod,
                'defaultInvoiceType' => $request->defaultInvoiceType ?? $config->defaultInvoiceType,
                'applyTax' => $request->applyTax ?? $config->applyTax,
                'taxTypes' => $request->taxTypes ?? $config->taxTypes,
                'allowedPaymentMethods' => $allowedPaymentMethods,
                'allowedInvoiceTypes' => $allowedInvoiceTypes
            ]);
        }

        return response()->json([
            'message' => 'Config updated successfully.',
            'config' => $config,
            'mainWarehouse' => Warehouse::where('is_default', true)->first()->name
        ]);
    }

    public function checkPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = User::find(1);

        if ($user->inviceConfig && $user->inviceConfig->password === $request->password) {
            return response()->json([
                'message' => 'Password is correct.'
            ]);
        } else {
            return response()->json([
                'message' => 'Password is incorrect.'
            ], 400);
        }
    }
}
