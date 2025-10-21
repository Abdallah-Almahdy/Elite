<?php

namespace App\Livewire\Orders;

use App\Models\CustomerInfo;
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
        $orderData = Order::with([
            'orderProducts.product',                         // المنتج نفسه
            'orderProducts.options.option',                  // تفاصيل الـ option
            'orderProducts.options.values.value',
            'orderProducts.addsOns.addsOn',          // قيمة الـ option
        ])->find($this->id);




        $orderData['promo_name'] = PromoCode::find($orderData['promo_code_id'])['code'] ?? null;
        $userInfo = CustomerInfo::where('user_id', $orderData->user_id)->get();



        // Handle address country names
        $addressCountry1Id = $userInfo[0]->addressCountry ? (int) $userInfo[0]->addressCountry : null;
        $addressCountry2Id = $userInfo[0]->addressCountry2 ? (int) $userInfo[0]->addressCountry2 : null;

        $addressCountryName = $addressCountry1Id
            ? Delivery::where('id', $addressCountry1Id)->value('name')
            : null;

        $addressCountry2Name = $addressCountry2Id
            ? Delivery::where('id', $addressCountry2Id)->value('name')
            : null;


        // Attach the country names to the user info object
        $userInfo[0]->addressCountryName = $addressCountryName;
        $userInfo[0]->addressCountry2Name = $addressCountry2Name;

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
        $tempAddress = $orderData->temp_address ? json_decode($orderData->temp_address, true) : null;

        // Determine the active address
        $finalAddress = $tempAddress ?? [
            'country' => $userInfo[0]->addressCountryName,
            'city' => $userInfo[0]->addresscity ?? null,
            'street' => $userInfo[0]->addressstreet ?? null,
            'building_number' => $userInfo[0]->addressbuildingNumber ?? null,
            'floor_number' => $userInfo[0]->addressfloorNumber ?? null,
            'apartment_number' => $userInfo[0]->addressApartmentNumber ?? null,
        ];

        $orderArray = [
            'id' => $orderData->id,
            'address' => $orderData->temp_address ?? null,
            'phone' => $orderData->phoneNumber,
            'status' => $orderData->status,
            'tracking_status' => $orderData->orderTracking[0]->status ?? 0,
            'notes' => $orderData->special_order_notes,
            'payment_method' => $orderData->payment_method,
            'order_type' => $orderData->order_type,
            'discount' => $orderData->discount,
            'total' => $orderData->totalPrice ?? 0,
            'promo_name' => $orderData->promo_name ?? null,
            'created_at' => $orderData->created_at,

            // بيانات العميل
            'customer' => [
                'name' => $userInfo[0]->firstName . ' ' . $userInfo[0]->lastName,
                'phone' => $userInfo[0]->phonenum,
                'finalAddress' => $finalAddress,
            ],

            // المنتجات
            'products' => $orderData->orderProducts->map(function ($orderProduct) {
                $product = $orderProduct->product;
                $basePrice = $product->price ?? 0;
                $count = $orderProduct->totalCount ?? 1;

                // 🔹 حساب سعر الـ options
                $optionsTotal = $orderProduct->options->flatMap(function ($opt) {
                    return $opt->values->map(function ($val) {
                        return $val->value->price ?? 0;
                    });
                })->sum();

                // 🔹 حساب سعر الـ adds_on
                $addsOnTotal = $orderProduct->addsOns->sum(function ($add) {
                    return $add->addsOn->price * $add->quantity ?? 0;
                });


                // 🔹 حساب إجمالي المنتج الكامل (سعر المنتج + الخيارات + الإضافات) × الكمية
                $totalrow = ($basePrice + $optionsTotal ) * $count + $addsOnTotal;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'photo' => $product->photo,
                    'section' => $product->section->name ?? 'غير محدد',
                    'price' => $basePrice,
                    'count' => $count,
                    'total' => $totalrow,
                    'discount' => $orderProduct->discount,

                    // الخيارات
                    'options' => $orderProduct->options->flatMap(function ($opt) {
                        return $opt->values->map(function ($val) use ($opt) {
                            return [
                                'option_name' => $opt->option->name ?? null,
                                'value_name' => $val->value->name ?? null,
                                'price' => $val->value->price ?? 0,
                            ];
                        });
                    }),

                    // الإضافات
                    'adds_on' => $orderProduct->addsOns->map(function ($add) {
                        return [
                            'id' => $add->addsOn->id ?? null,
                            'name' => $add->addsOn->name ?? null,
                            'price' => $add->addsOn->price ?? 0,
                            'active' => $add->addsOn->active ?? 0,
                            "quantity"=> $add->quantity
                        ];
                    }),
                ];
            }),
        ];



        // dd($orderData->orderTracking[0]->status);
        return view('livewire.orders.order-details', [
            'orderData' => $orderData,
            'userInfo' => $userInfo[0],
            'orderProdutcs' => $orderProdutcs,
            'printData' =>  $printData,
            'orderArray' => $orderArray
        ]);
    }
}
