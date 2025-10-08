<?php

namespace App\Livewire\Orders;

use App\Models\CustomerInfo;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;

class OrderDetails extends Component
{

    public $id;


    #[Layout('admin.livewireLayout')]
    public function render()
    {
        $orderData = Order::find($this->id);
        $userInfo = CustomerInfo::where('user_id', $orderData->user_id)->get();
        $orderProdutcs = [];


        foreach ($orderData->orderProducts as $product) {
            $orderProdutcs[] = [
                'porductData' => Product::find($product->product_id),
                'porductCount' =>  $product->totalCount,
                'porductTotalPrice' =>  $product->totalPrice
            ];
            // $orderProdutcs[] = Product::find($product->product_id);
            // $orderProdutcs[]->productCount = $product->totalCount;
        }

        $printData = [
            'orderData' => $orderData,
            'userInfo' => $userInfo[0],
            'orderProdutcs' => $orderProdutcs
        ];
        // dd($orderData->orderTracking[0]->status);
        return view('livewire.orders.order-details', [
            'orderData' => $orderData,
            'userInfo' => $userInfo[0],
            'orderProdutcs' => $orderProdutcs,
            'printData' =>  $printData
        ]);
    }
}
