<?php

namespace App\Livewire\Orders;

use Carbon\Carbon;
use App\Models\CustomerInfo;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{

    public function done($id)
    {
        Order::where('id', $id)->update(['status' => 1]);
        OrderTracking::where('order_id', $id)->update(['status' => 3]);
    }
    public function prep($id)
    {
        OrderTracking::where('order_id', $id)->update(['status' => 1]);
    }
    public function delivery($id)
    {
        OrderTracking::where('order_id', $id)->update(['status' => 2]);
    }
    public function cancel($id)
    {
        Order::where('id', $id)->update(['status' => 2]);
        OrderTracking::where('order_id', $id)->update(['status' => 3]);
    }



    // public function confirmOrder($id)
    // {
    // $order = Order::with('orderProducts.product.recipe.ingredients.unit')->findOrFail($id);
    // $order->update(['status' => 0]);
    // foreach ($order->orderProducts as $orderProduct) {
    //     $product = $orderProduct->product;
    //     if ($product->hasRecipe() && $product->recipe) {
    //         foreach ($product->recipe->ingredients as $ingredient) {
    //             $neededQty = $ingredient->pivot->quantity_needed * $orderProduct->totalCount;
    //             $recipeUnit = $ingredient->pivot->unit_id
    //                           ? \App\Models\Unit::find($ingredient->pivot->unit_id)
    //                           : $ingredient->unit;
    //             $baseUnitQty = $recipeUnit->convertToBase($neededQty);
    //             $ingredient->quantity_in_stock -= $baseUnitQty;
    //             $ingredient->save();
    //         }
    //     }
    // }

    // session()->flash('success', '✅ تم تأكيد الطلب وخصم المكونات من المخزون');
    // }




    #[Layout('admin.livewireLayout')]
    public function render()
    {

        $orderBasic = [];

        // Get today's date
        $today = Carbon::today();

        // Query orders with status 0 (pending) for today
        $orders = Order::where('status', 0)
            ->get();

        // Query failed orders (status 2) for today
        $faildOrders = Order::where('status', 2)
            ->whereDate('created_at', $today)
            ->get();

        // Query successful orders (status 1) for today
        $successedOrders = Order::where('status', 1)
            ->whereDate('created_at', $today)
            ->get();
        $inPreperOrders = [];
        $inDeliveryOrders = [];

        foreach ($orders as $order) {



            switch ($order->orderTracking[0]->status) {
                case 0:
                    $orderBasic[] = $order;
                    break;
                case 1:
                    $inPreperOrders[] = $order;
                    break;
                case 2:
                    $inDeliveryOrders[] = $order;
                    break;
                default:
                    break;
            }
        }


        foreach ($orders as $order) {
            $order['user_info'] = CustomerInfo::where('user_id', $order->user_id)->get()[0];
        }
        foreach ($faildOrders as $order) {
            $order['user_info'] = CustomerInfo::where('user_id', $order->user_id)->get()[0];
        }
        foreach ($successedOrders as $order) {
            $order['user_info'] = CustomerInfo::where('user_id', $order->user_id)->get()[0];
        }
        foreach ($inPreperOrders as $order) {
            $order['user_info'] = CustomerInfo::where('user_id', $order->user_id)->get()[0];
        }
        foreach ($inDeliveryOrders as $order) {
            $order['user_info'] = CustomerInfo::where('user_id', $order->user_id)->get()[0];
        }


        return view('livewire.orders.index', [
            'orders' => collect($orderBasic)->reverse(),
            'faildOrders' => collect($faildOrders)->reverse(),
            'successedOrders' => collect($successedOrders)->reverse(),
            'inPreperOrders' => collect($inPreperOrders)->reverse(),
            'inDeliveryOrders' => collect($inDeliveryOrders)->reverse(),
        ]);
    }
}
