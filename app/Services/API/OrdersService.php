<?php

namespace App\Services\API;

use App\Http\Controllers\Api\PaymentController;
use App\Models\AddsOn;
use App\Models\Order;
use App\Models\Product;
use App\Models\Section;
use App\Traits\ApiTrait;
use App\Models\OrderProduct;
use App\Models\CustomerInfo;
use App\Models\Delivery;
use App\Models\OptionsValues;
use App\Models\OrderTracking;
use App\Models\OrderProductAddsOn;
use App\Models\OrderProductOption;
use App\Services\API\PromocodeService;
use App\Models\OrderProductOptionValue;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Nafezly\Payments\Classes\PayeerPayment;
use Illuminate\Support\Facades\DB;

class OrdersService
{
    /*
    |--------------------------------------------------------------------------
    | IOC
    |--------------------------------------------------------------------------
    */
    use ApiTrait;

    protected $promocodeService;

    public function __construct(PromocodeService $promocodeService)
    {
        $this->promocodeService = $promocodeService;
    }

    /*
    |--------------------------------------------------------------------------
    | main methods
    |--------------------------------------------------------------------------
    */
    public function makeOrder($request, $userId, $promocodeId = null)
    {

        try {
            DB::beginTransaction();
            $orderData =  $this->makeOrderLogic($request);


            if ($promocodeId) {
                $promoCodeResponse = $this->promocodeService->checkPromocode($request, $orderData->totalPrice);
                if ($promoCodeResponse->getStatusCode() != 200) {
                    DB::rollBack();
                    return $promoCodeResponse;
                }
                $finalTotal = $promoCodeResponse->getData()->data->final_total;
                $orderData->totalPrice = $finalTotal;
                $orderData->discount = $orderData->totalPrice - $finalTotal;
                $orderData->save();
            }






            $this->promocodeService->markPromocodeAsUsed($promocodeId, $orderData->id, $userId);



            if ($orderData->payment_method == 0) {
                $paymentmodel = Payment::create([
                    'order_id' => $orderData->id,
                    'provider' => 'paymob',
                    'amount' => $orderData->totalPrice,
                    'status' => 'pending',
                ]);


                $payment = new PaymentController;
                $data = [
                    "amount" => $orderData->totalPrice,
                    "user_id" => $request->user()->id,
                    "user_first_name" => "abdallah",
                    "user_last_name" => "omar",
                    "user_email" => "abdalla@email.com",
                    "user_phone" => "01203571855",
                    "order_id" => 1

                ];

                $response  =  $payment->pay($data);

                $paymentmodel->update([
                    'provider_payment_id' => $response['payment_id'],
                ]);

                DB::commit();

                return response()->json([
                    'iframe_url' => $response['redirect_url']
                ]);
            }

            return response()->json([
                "data" => "order created"
            ]);
        } catch (\Exception $e) {

            DB::rollBack(); // ❌ في حالة أي خطأ نرجع كل حاجة
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الطلب',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cancelOrder($orderId)
    {
        $this->checkExists(order::class, 'id', $orderId);
        $order = order::find($orderId);

        $this->promocodeService->restorePromocodeUsage($order);
        $order->status =2;
        // 1 = pre 3 = cancel 2 = succ
        orderTracking::where('order_id', $orderId)->update(['status' => 5]);
        $order->save();


    }

    private function makeOrderLogic($request)
    {

        $userId = $request->user()->id;
        $userAddress = $request->userAddress;
        $orderPaymentMethod = $request->orderPaymentMethod;
        $promocodeId = $request->promocode_id;
        $orderProducts = $request->orderProducts;

        // calac the total price from profucts
        $order =
            [
                'user_id' => $userId,
                'totalPrice' => 0,
                'address' => $userAddress,
                'phoneNumber' => CustomerInfo::where('user_id', $userId)->value('phonenum'),
                'status' => 0,
                'payment_method' => $orderPaymentMethod,
                'order_type' => 0,
                'special_order_notes' => $request->special_order_notes ?? null,
            ];


        // orderPaymentMethod // 0 credit 1, cash
        // orderType // 0 = delivery, 1 = takeaway, 2 = in


        if ($userAddress == 3) {
            $addressData = [
                'country' => $request->addressCountry,
                'city' => $request->addressCity,
                'street' => $request->addressStreet,
                'building_number' => $request->addressBuildingNumber ?? null,
                'floor_number' => $request->addressFloorNumber ?? null,
                'apartment_number' => $request->addressApartmentNumber ?? null,
            ];

            $addressJson = json_encode($addressData, JSON_UNESCAPED_UNICODE);


            $order['temp_address'] = $addressJson;
        }



        if ($promocodeId) {
            $order['promo_code_id'] = $promocodeId;
        }

        $orderData = Order::create($order);

        orderTracking::create([
            'user_id'  => $orderData->user_id,
            'order_id' => $orderData->id,
            'status' => 0,
        ]);

        $totalPrice = 0;
        foreach ($orderProducts as $product) {
            $productModel = Product::find($product['productId']);
            if (!$productModel) continue;

            // إنشاء OrderProduct
            $orderProduct = OrderProduct::create([
                'product_id' => $productModel->id,
                'order_id' => $orderData->id,
                'totalCount' => $product['quantity'],
                'totalPrice' => $product['quantity'] * $productModel->price,
            ]);
            $totalPrice += $orderProduct->totalPrice;

            // لو عنده إضافات (Addons)
            if (!empty($product['addson'])) {
                foreach ($product['addson'] as $addonId) {
                    OrderProductAddsOn::create([
                        'order_product_id' => $orderProduct->id,
                        'adds_on_id' => $addonId,
                        'active' => 1,
                    ]);
                    $addon = AddsOn::find($addonId);
                    $totalPrice += $addon->price;
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

                    $totalPrice += OptionsValues::find($optionData['valueId'])->price;
                }
            }
        }


        $orderData->totalPrice =  $totalPrice;
        $orderData->save();


        return $orderData;
    }

    public function getAllUserOrders($user)
    {


        $orders = Order::with([
            // المنتجات المرتبطة بالطلب
            'orderProducts.product.section',  // المنتج وقسمه

            // الخيارات الخاصة بكل منتج
            'orderProducts.options.option',   // تفاصيل الـ option نفسه
            'orderProducts.options.values.value', // القيم الخاصة بكل option

            // الإضافات (Adds On)
            'orderProducts.addsOns.addsOn',   // كل Addon مرتبط بالمنتج

            // تتبع الطلب (tracking)
            'orderTracking',                  // حالة التتبع لكل طلب

            // بيانات المستخدم
            'user',                           // بيانات المستخدم صاحب الطلب
        ])->where('user_id', $user->id)->get();


        $orderArray = $orders->map(function ($orderData) use ($user) {
            return [ // 👈 لازم return هنا
                'id' => $orderData->id,
                'address' => $this->address($orderData, $user),
                'phone' => $orderData->phoneNumber,
                'status' => $orderData->status,
                'tracking_status' => $orderData->orderTracking[0]->status ?? 0, // حالة التتبع
                'notes' => $orderData->special_order_notes,
                'payment_method' => $orderData->payment_method,
                'order_type' => $orderData->order_type,
                'discount' => $orderData->discount,
                'total' => $orderData->totalPrice ?? 0,
                'created_at' => $orderData->created_at,

                // بيانات العميل
                'customer' => [
                    'name' => $user->customerInfo->firstName . " ". $user->customerInfo->lastName,
                    'phone' => $user->customerInfo->phonenum,
                ],

                // المنتجات
                'products' => $orderData->orderProducts->map(function ($orderProduct) {
                    $product = $orderProduct->product;
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'photo' => $product->photo,
                        'section' => $product->section->name ?? 'غير محدد',
                        'price' => $product->price ?? 0,
                        'count' => $orderProduct->totalCount,
                        'total' => $orderProduct->totalPrice,

                        // الخيارات الخاصة بالمنتج
                        'options' => $orderProduct->options->flatMap(function ($opt) {
                            return $opt->values->map(function ($val) use ($opt) {
                                return [
                                    'option_name' => $opt->option->name ?? null,
                                    'value_name' => $val->value->name ?? null,
                                    'price' => $val->value->price ?? null,
                                ];
                            });
                        }),

                        // الإضافات الخاصة بالمنتج (AddsOn)
                        'adds_on' => $orderProduct->addsOns->map(function ($add) {
                            return [
                                'id' => $add->addsOn->id ?? null,
                                'name' => $add->addsOn->name ?? null,
                                'price' => $add->addsOn->price ?? 0,
                                'active' => $add->active ?? 0, // ✅ مش $add->addsOn->active
                            ];
                        }),
                    ];
                }),
            ];
        });


        return response()->json([
            "data" => $orderArray
        ]);
    }


    public function address($orderData, $user)
    {
        $userInfo = CustomerInfo::where('user_id', $user->id)->get();



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


        return $finalAddress;
    }
}
