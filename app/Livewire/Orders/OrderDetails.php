<?php

namespace App\Livewire\Orders;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\Product;
use App\Models\PromoCode;
use Livewire\Component;
use Livewire\Attributes\Layout;

class OrderDetails extends Component
{
    public $id;

    #[Layout('admin.livewireLayout')]
    public function render()
    {
        // Load order with all necessary relations
        $order = Order::with([
            'user.userProfile',
            'user.userAddresses',
            'orderProducts.product.section',
            'orderProducts.options.option',
            'orderProducts.options.values.value',
            'orderProducts.addsOns.addsOn',
            'orderTracking'
        ])->findOrFail($this->id);

        $user = $order->user;
        $profile = $user?->userProfile;
        $addresses = $user?->userAddresses ?? collect();

        // Get the default address or the first one
        $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();

        // Handle temporary address from order (if any)
        $tempAddress = $order->temp_address ? json_decode($order->temp_address, true) : null;

        // Build final address array
        if ($tempAddress) {
            // Use temp address if available (it already contains country, city, etc.)
            $finalAddress = $tempAddress;
        } else {
            // Build from default address and user profile fields
            $finalAddress = [
                'country' => $this->getCountryName($defaultAddress?->delivery_place_id),
                'city' => $defaultAddress?->address_city,
                'street' => $defaultAddress?->address_street,
                'building_number' => $defaultAddress?->address_building,
                'floor_number' => $defaultAddress?->address_floor,
                'apartment_number' => $defaultAddress?->address_apartment,
            ];
        }

        // Get promo code name
        $promoName = $order->promo_code_id ? PromoCode::find($order->promo_code_id)?->code : null;

        // Build order array for the view
        $orderArray = [
            'id' => $order->id,
            'status' => $order->status,
            'tracking_status' => $order->orderTracking->first()?->status ?? 0,
            'notes' => $order->special_order_notes,
            'payment_method' => $order->payment_method,
            'discount' => $order->discount ?? 0,
            'total' => $order->totalPrice ?? 0,
            'promo_name' => $promoName,
            'created_at' => $order->created_at,
            'customer' => [
                'name' => ($profile?->first_name ?? '') . ' ' . ($profile?->last_name ?? ''),
                'phone' => $profile?->phone_number ?? $order->phoneNumber ?? '',
                'finalAddress' => $finalAddress,
            ],
            'products' => $order->orderProducts->map(function ($orderProduct) {

                $product = $orderProduct->product;
                $basePrice = $orderProduct->totalPrice /  $orderProduct->totalCount  ?? 0;
                $count = $orderProduct->totalCount ?? 1;

                // Calculate options total
                $optionsTotal = $orderProduct->options->flatMap(function ($opt) {
                    return $opt->values->map(function ($val) {
                        return $val->value->price ?? 0;
                    });
                })->sum();

                // Calculate adds-on total
                $addsOnTotal = $orderProduct->addsOns->sum(function ($add) {
                    return ($add->addsOn->price ?? 0) * ($add->quantity ?? 0);
                });

                // Total per row: (base + options) * count + adds-on
                $totalRow = ($basePrice + $optionsTotal) * $count + $addsOnTotal;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'photo' => $product->photo,
                    'section' => $product->section?->name ?? 'غير محدد',
                    'price' => $basePrice,
                    'count' => $count,
                    'total' => $totalRow,
                    'discount' => $orderProduct->discount ?? 0,
                    'options' => $orderProduct->options->flatMap(function ($opt) {
                        return $opt->values->map(function ($val) use ($opt) {
                            return [
                                'option_name' => $opt->option?->name,
                                'value_name' => $val->value?->name,
                                'price' => $val->value?->price ?? 0,
                            ];
                        });
                    }),
                    'adds_on' => $orderProduct->addsOns->map(function ($add) {
                        return [
                            'id' => $add->addsOn?->id,
                            'name' => $add->addsOn?->name,
                            'price' => $add->addsOn?->price ?? 0,
                            'quantity' => $add->quantity,
                        ];
                    }),
                ];
            }),
        ];

        return view('livewire.orders.order-details', [
            'orderArray' => $orderArray,
        ]);
    }

    /**
     * Helper to get country name from delivery_place_id
     */
    private function getCountryName($deliveryPlaceId)
    {
        if (!$deliveryPlaceId) {
            return null;
        }
        return Delivery::where('id', $deliveryPlaceId)->value('name');
    }
}
