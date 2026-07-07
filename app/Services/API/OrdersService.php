<?php

namespace App\Services\API;

use App\Models\AddsOn;
use App\Models\OptionsValues;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductAddsOn;
use App\Models\OrderProductOption;
use App\Models\OrderProductOptionValue;
use App\Models\OrderTracking;
use App\Models\Product;
use App\Models\ProductUnits;
use App\Models\User;
use Exception;

class OrdersService
{



    public function create($request)
    {
        $data =   $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|in:cash,credit_card,instapay,wallet,remaining',
            'promo_code_id' => 'nullable|exists:promo_codes,id',
            'order_type' => 'required|in:delivery,takeaway,in-restaurant',
            'temp_address' => 'nullable|string',
            "special_order_notes" => "nullable|string",
            'orderProducts' => 'required|array',
            'orderProducts.*.product_id' => 'required|exists:products,id',
            'orderProducts.*.quantity' => 'required|integer|min:1',
            'orderProducts.*.unit_id' => 'required|exists:units,id',
            'orderProducts.*.unit_conversion_factor' => 'required|numeric|min:0.01',
            
        ]);



        $data['user_id'] = $request->user()->id;

        $order = $this->createOrder($data);

        $order = $this->createOrderProducts($order, $data);
        $this->orderTracking($order);

        return $order;

    }




    public function createOrder($data)
    {
        $user = User::find($data['user_id']);

        $address = $user->userAddresses()->find($data['address_id']);
        $orderData = [
            'user_id' => $data['user_id'],
            'address' => $address->address_city,
            'payment_method' => $data['payment_method'],
            'promo_code_id' => $data['promo_code_id'] ?? null,
            'order_type' => $data['order_type'],
            'temp_address' => $data['temp_address'] ?? null,
            'special_order_notes' => $data['special_order_notes'],
            'discount' => 0,
            'totalPrice' => 0,
            'phoneNumber' => $user->profile->phone_number,
            'status' => 0
        ];

        $order = Order::create($orderData);
        return $order;
    }

    public function createOrderProducts($order, $data)
    {
        $totalPrice = 0;
        foreach ($data['orderProducts'] as $product)
        {

            $productModel = Product::find($product['product_id']);

            if (!$productModel) continue;

            $unit = $productModel->units->first(function ($unit) use ($product) {
                return (float) $unit->pivot->conversion_factor === (float) $product['unit_conversion_factor'];
            });

            if (!$unit) {
                throw new Exception("Conversion factor not exists.");
            }
            // إنشاء OrderProduct
            $orderProduct = OrderProduct::create([
                'product_id' => $productModel->id,
                'order_id' => $order->id,
                'totalCount' => $product['quantity'],
                'totalPrice' => $product['quantity'] * $unit->pivot->sallprice,
                'discount' => ($productModel->price * ($productModel->offer_rate / 100)),
                'unit_id' => $product['unit_id'],
                'unit_conversion_factor' => $product['unit_conversion_factor'],

            ]);
            $totalPrice += $orderProduct->totalPrice - ($orderProduct->discount * $orderProduct->totalCount);

        }


        $order->totalPrice = $totalPrice;
        $order->save();
        return $order;
    }

    public function  orderTracking($order){

        OrderTracking::create([
            'user_id'  => $order->user_id,
            'order_id' => $order->id,
            'status' => 0,
        ]);
    }



}
