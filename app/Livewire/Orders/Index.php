<?php

namespace App\Livewire\Orders;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderTracking;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $activeTab = 'new';
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

    #[Layout('admin.livewireLayout')]
    public function render()
    {
        $today = Carbon::today();

        // Pending orders: load user, userProfile, and orderTracking
        $pendingOrders = Order::with(['user.userProfile', 'orderTracking'])
            ->where('status', 0)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('payment_method', 0)
                        ->where('payment_status', 'paid');
                })
                    ->orWhere('payment_method', '!=', 0);
            })
            ->get();

        // Completed and failed orders for today
        $completedOrders = Order::with('user.userProfile')
            ->where('status', 1)
            ->whereDate('created_at', $today)
            ->get();

        $failedOrders = Order::with('user.userProfile')
            ->where('status', 2)
            ->whereDate('created_at', $today)
            ->get();

        // Group pending orders by tracking status
        $newOrders = collect();
        $preparingOrders = collect();
        $deliveryOrders = collect();

        foreach ($pendingOrders as $order) {
            $trackingStatus = $order->orderTracking->first()->status ?? 0;
            switch ($trackingStatus) {
                case 0:
                    $newOrders->push($order);
                    break;
                case 1:
                    $preparingOrders->push($order);
                    break;
                case 2:
                    $deliveryOrders->push($order);
                    break;
                default:
                    break;
            }
        }

        return view('livewire.orders.index', [
            'newOrders'       => $newOrders->reverse(),
            'preparingOrders' => $preparingOrders->reverse(),
            'deliveryOrders'  => $deliveryOrders->reverse(),
            'completedOrders' => $completedOrders->reverse(),
            'failedOrders'    => $failedOrders->reverse(),
        ]);
    }
}
