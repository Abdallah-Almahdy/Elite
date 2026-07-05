<?php

namespace App\Services\API;

use App\Models\AddsOn;
use App\Models\OptionsValues;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductAddsOn;
use App\Models\OrderProductOption;
use App\Models\OrderProductOptionValue;
use App\Models\Product;
use App\Models\User;

class OrdersService
{



    public function create($request)
    {
        $data =   $request->validate([
            'address' => 'required|string',
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
    }




    public function createOrder($data)
    {
        $user = User::find($data['user_id']);

        $orderData = [
            'user_id' => $data['user_id'],
            'address' => $data['address'],
            'payment_method' => $data['payment_method'],
            'promo_code_id' => $data['promo_code_id'],
            'order_type' => $data['order_type'],
            'temp_address' => $data['temp_address'] ?? null,
            'special_order_notes' => $data['special_order_notes'],
            'discount' => 0,
            'totalPrice' => 0,
            'phoneNumber' => $user->profile->phone_number ?? null,
        ];

        $order = Order::create($data);
        return $order;
    }

    public function createOrderProducts($order, $data)
    {
        foreach ($data['orderProducts'] as $product) {
            $productModel = Product::find($product['product_id']);
            if (!$productModel) continue;

            // إنشاء OrderProduct
            $orderProduct = OrderProduct::create([
                'product_id' => $productModel->id,
                'order_id' => $order->id,
                'totalCount' => $product['quantity'],
                'totalPrice' => $product['quantity'] * $productModel->price,
                'discount' => ($productModel->price * ($productModel->offer_rate / 100)),
                'unit_id' => $product['unit_id'],
                'unit_conversion_factor' => $product['unit_conversion_factor'],

            ]);

            $totalPrice += $orderProduct->totalPrice - ($orderProduct->discount * $orderProduct->totalCount);

            // لو عنده إضافات (Addons)
            if (!empty($product['addson']))
            {
                foreach ($product['addson'] as $addon)
                {
                    $OrderProductAddsOn = OrderProductAddsOn::create([
                        'order_product_id' => $orderProduct->id,
                        'adds_on_id' => $addon['id'],
                        'active' => 1,
                        'quantity' => $addon['quantity']
                    ]);

                    $addon = AddsOn::find($addon['id']);
                    $totalPrice += $addon->price  *  $OrderProductAddsOn->quantity;
                }
            }

            if (!empty($product['options'])) {
                foreach ($product['options'] as $optionData) {
                    $orderOption = OrderProductOption::create([
                        'order_product_id' => $orderProduct->id,
                        'option_id' => $optionData['optionId'],
                    ]);

                    // values (array of option_value_ids)
                    OrderProductOptionValue::create([
                        'order_product_option_id' => $orderOption->id,
                        'option_value_id' => $optionData['valueId'],
                        'active' => 1
                    ]);

                    $totalPrice += OptionsValues::find($optionData['valueId'])->price * $product['quantity'];
                }
            }
        }
    }
}
