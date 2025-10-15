<?php

namespace App\Services\API;

use App\Http\Controllers\Api\PaymentController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Section;
use App\Traits\ApiTrait;
use App\Models\orderProduct;
use App\Models\CustomerInfo;
use App\Models\orderTracking;
use App\Models\OrderProductAddsOn;
use App\Models\OrderProductOption;
use App\Services\API\PromocodeService;
use App\Models\OrderProductOptionValue;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Nafezly\Payments\Classes\PayeerPayment;

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
        if ($promocodeId) {
            $promoCodeResponse = $this->promocodeService->checkPromocode($request);
            if ($promoCodeResponse->getStatusCode() != 200) {
                return $promoCodeResponse;
            }
        }
        // Create main order
        $orderData =  $this->makeOrderLogic($request);



        // Mark promo as used

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

            return response()->json([
                'iframe_url' => $response['redirect_url']
            ]);
        }

        return response()->json([
            "data" => "order created"
        ]);
    }

    public function cancelOrder($orderId)
    {
        $this->checkExists(order::class, 'id', $orderId);
        $order = order::find($orderId);
        $this->promocodeService->restorePromocodeUsage($order);
        $order->status = 2;
        orderTracking::where('order_id', $orderId)->update(['status' => 3]);
        $order->save();
    }

    public function getAllUserOrders($userId, $request)
    {
        $this->checkExists(User::class, 'id', $userId);

        $user = User::find($userId);

        $userOrdersData = [];
        $userInfo = CustomerInfo::where('user_id', $userId)->get()[0];
        if (!$userInfo) {
            return $this->notFound('couldn\'t find the data for this user, or user does not exist');
        }

        $this->getUserData($userOrdersData, $userInfo);

        $this->getUserOrders($user, $userOrdersData, $request);

        return $userOrdersData;
    }


    private function makeOrderLogic($request)
    {

        $userId = $request->user()->id;
        $orderTotalPrice = $request->orderTotalPrice;
        $userAddress = $request->userAddress;
        $orderPaymentMethod = $request->orderPaymentMethod;
        $promocodeId = $request->promocode_id;
        $orderProducts = $request->orderProducts;


        $order =
            [
                'user_id' => $userId,
                'totalPrice' => $orderTotalPrice,
                'address' => $userAddress,
                'phoneNumber' => 0123,
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

            // لو عنده إضافات (Addons)
            if (!empty($product['addons'])) {
                foreach ($product['addons'] as $addonId) {
                    OrderProductAddsOn::create([
                        'order_product_id' => $orderProduct->id,
                        'adds_on_id' => $addonId,
                        'active' => 1,
                    ]);
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
                }
            }
        }

        // Track order
        orderTracking::create([
            'user_id'  => $orderData->user_id,
            'order_id' => $orderData->id,
            'status' => 0,
        ]);


        return $orderData;
    }

    protected function getUserOrders($user, $UserOrders, $request)
    {
        $UserOrders['user_orders'] = [];

        foreach ($user->orders as $order) {
            $orderProducts = [];
            $Products = $order->orderProducts;
            $userInfo = CustomerInfo::where('user_id', $order->user_id)->get()[0];

            foreach ($Products as $product) {
                $productInfo = Product::find($product->product_id);
                $category = Section::find($productInfo->section_id);


                $orderProducts[] =  [
                    'productInfo' => [
                        "id" => $product->product_id,
                        "name" => $productInfo->name,
                        "detail" => $productInfo->description,
                        "price" => $productInfo->price,
                        "offer_rate" => $productInfo->offer_rate,
                        "unit_name" => 'ج.م',
                        "image" => env('IMG_BASE_LINK') . $productInfo->photo,
                        "category" => $category->name,
                    ],
                    'quantity' => $product->totalCount
                ];
            }


            $address = $this->getUserAddres($order->address, $userInfo, $order);


            $UserOrders['user_orders'][] =
                [
                    "orderId" => $order->id,
                    "orderTotalPrice" => $order->totalPrice,
                    "orderStatus" => $order->status,
                    "orderTrackingStatus" =>  orderTracking::where('user_id', $request->query('user_id'))
                        ->where('order_id', $order->id)
                        ->latest()
                        ->first()->status,
                    "orderPaymentMethod" => $order->payment_method,
                    "orderDate" => $order->created_at->format('Y-m-d H:i:s'),
                    "userAddress" =>  $address,
                    "orderProducts" =>  $orderProducts,
                ];
        }
    }

    protected function getUserData($UserOrders, $userInfo)
    {

        $UserOrders['user_data'] = [
            "userName" =>  $userInfo->firstName . $userInfo->lastName,
            "userPhoneNumer" => $userInfo->phonenum,
        ];
    }

    protected function getUserAddres($addressNum, $userInfo)
    {
        $address = null;
        if ($addressNum == 1) {
            $address =
                $userInfo->addressCountry .
                ' ,' .
                $userInfo->addresscity .
                ' ,' .
                $userInfo->addressstreet .
                ' ,' .
                $userInfo->addressbuildingNumber .
                ' ,' .
                $userInfo->addressfloorNumber .
                ' ,' .
                $userInfo->addressApartmentNumber .
                ' ,' .
                $userInfo->disSign;
        } elseif ($addressNum->address == 2) {
            $address =
                $userInfo->addressCountry2 .
                ' ,' .
                $userInfo->addresscity2 .
                ' ,' .
                $userInfo->addressstreet2 .
                ' ,' .
                $userInfo->addressbuildingNumber2 .
                ' ,' .
                $userInfo->addressfloorNumber2 .
                ' ,' .
                $userInfo->addressApartmentNumber2 .
                ' ,' .
                $userInfo->disSign2;
        } else {
            $address = null;
        }
        return $address;
    }
}
