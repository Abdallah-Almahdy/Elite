<?php

namespace App\Http\Controllers\Back\Statics;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Models\CustomerInfo;
use App\Http\Controllers\Controller;

class StaticsOrdersController extends Controller
{
    public function orders(Request $request)
    {
        $query = Order::with('user_info');


        // $userID = User::where('email', $request->input('search'))->first()->id;

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('phoneNumber', 'like', "%$search%");
            });
        }
        $orders = $query->paginate(25)->appends(['search' => $search]);

        foreach ($orders as $order) {
            $order->userData = User::find($order->user_id);
        }
        return view('admin.statics.orders.orders', [
            'orders' => $orders
        ]);
    }

    public function orderInfo($id)
    {

        $order = Order::find($id);
        $user = User::find($order->user_id);
        $addressCountryName = $user->customerInfo->addressCountry
            ? Delivery::where('id', $user->customerInfo->addressCountry)->value('name')
            : null;

        $addressCountry2Name = $user->customerInfo->addressCountry2
            ? Delivery::where('id', $user->customerInfo->addressCountry2)->value('name')
            : null;


        $orderProdutcs = [];
        foreach ($order->orderProducts as $product) {
            $orderProdutcs[] = [
                'porductData' => Product::find($product->product_id),
                'porductCount' =>  $product->totalCount,
                'porductTotalPrice' =>  $product->totalPrice,
                'porductPrice' =>  $product->totalPrice / $product->totalCount
            ];
            // $orderProdutcs[] = Product::find($product->product_id);
            // $orderProdutcs[]->productCount = $product->totalCount;
        }


        return view('admin.statics.orders.orderInfo', [
            'order' => $order,
            'user' => $user,
            'addressCountryName' => $addressCountryName,
            'addressCountry2Name' => $addressCountry2Name,
            'orderProdutcs' => $orderProdutcs,
        ]);
    }


    public function successOrders(Request $request)
    {
        $query = Order::with('user_info');

        // $userID = User::where('email', $request->input('search'))->first()->id;

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('phoneNumber', 'like', "%$search%");
            });
        }
        $orders = $query->where('status', 1)->paginate(25)->appends(['search' => $search]);

        foreach ($orders as $order) {
            $order->userData = User::find($order->user_id);
        }
        return view('admin.statics.orders.successOrders', [
            'orders' => $orders
        ]);
    }
    public function faildOrders(Request $request)
    {
        $query = Order::with('user_info');

        // $userID = User::where('email', $request->input('search'))->first()->id;

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('phoneNumber', 'like', "%$search%");
            });
        }
        $orders = $query->where('status', 2)->paginate(25)->appends(['search' => $search]);

        foreach ($orders as $order) {
            $order->userData = User::find($order->user_id);
        }
        return view('admin.statics.orders.successOrders', [
            'orders' => $orders
        ]);
    }
}
