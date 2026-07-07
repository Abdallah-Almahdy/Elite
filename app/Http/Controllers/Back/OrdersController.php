<?php

namespace App\Http\Controllers\Back;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomerInfo;
use Illuminate\Support\Facades\Gate;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('showOrdersSidebar');

        $orders = Order::all();


        return view('pages.orders.index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function print($id)
    {

        $orderData = Order::find($id);
        $userInfo = CustomerInfo::where('user_id', $orderData->user_id)->get();
        $orderProdutcs = [];


        foreach ($orderData->orderProducts as $product) {
            $orderProdutcs[] = [
                'porductData' => Product::find($product->product_id),
                'porductCount' =>  $product->totalCount,
                'porductTotalPrice' =>  $product->totalPrice
            ];
        }

        $printData = [
            'orderData' => $orderData,
            'userInfo' => $userInfo[0],
            'orderProdutcs' => $orderProdutcs
        ];
        return view('pages.orders.print', ['data' => $printData]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        //
    }

    public function validateCart(Request $request)
    {
        $data = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.unit_conversion_factor' => 'required|numeric|min:0.0001',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        $products = Product::with([
            'units',
            'defaultWarehouse'
        ])
            ->whereIn('id', collect($data['products'])->pluck('id'))
            ->get()
            ->keyBy('id');



        $changes = [];

        foreach ($data['products'] as $item) {

            $product = $products->get($item['id']);

            if (!$product) {
                continue;
            }

            if (!$product->active) {
                $changes[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'changes' => [
                        'status' => [
                            'type' => 'inactive',
                            'message' => 'This product is no longer available.',
                        ],
                    ],
                ];

                continue;
            }
            $unit = $product->units->first(function ($unit) use ($item) {
                return (float) $unit->pivot->conversion_factor === (float) $item['unit_conversion_factor'];
            });


            if (!$unit) {
                $changes[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'changes' => [
                        'status' => [
                            'type' => 'unit_not_available',
                            'message' => 'This product is no longer available with the selected unit.',
                        ],
                    ],
                ];

                continue;
            }

            $currentPrice = $unit->pivot->sallprice;
            $availableQuantity = $product->defaultWarehouse->first()?->pivot?->quantity ?? 0;

            $productChanges = [];

            // التحقق من السعر
            if ((float) $item['price'] !== (float) $currentPrice) {
                $productChanges['price'] = [
                    'old' => (float) $item['price'],
                    'new' => (float) $currentPrice,
                ];
            }

            // التحقق من الكمية
            if ($item['quantity'] > $availableQuantity) {
                $productChanges['quantity'] = [
                    'requested' => (float) $item['quantity'],
                    'available' => (float) $availableQuantity,
                ];
            }

            if (!empty($productChanges)) {
                $changes[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'changes' => $productChanges,
                ];
            }
        }

        if (empty($changes)) {
            return response()->json([
                'success' => true,
                'message' => 'Cart is valid.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Some products have changed.',
            'products' => $changes
        ], 422);
    }
}
