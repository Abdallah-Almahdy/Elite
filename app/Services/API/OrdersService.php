<?php

namespace App\Services\API;

use App\Models\order;
use App\Models\product;
use App\Models\section;
use App\Traits\ApiTrait;
use App\Models\orderProduct;
use App\Models\CustomerInfo;
use App\Models\orderTracking;
use App\Models\OrderProductAddsOn;
use App\Models\OrderProductOption;
use App\Services\API\PromocodeService;
use App\Models\OrderProductOptionValue;
use App\Models\User;

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
    public function makeOrder($request, $userId, $promocodeId)
    {
        if ($promocodeId) {
            $promoCodeResponse = $this->promocodeService->checkPromocode($request);
            if ($promoCodeResponse->getStatusCode() != 200) {
                return $promoCodeResponse;
            }
        }

        $this->checkExists(User::class, 'id', $userId);

        // Create main order
        $orderData =  $this->makeOrderLogic($request);

        // Mark promo as used
        $this->promocodeService->markPromocodeAsUsed($promocodeId, $orderData->id, $userId);
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

    /*
    |--------------------------------------------------------------------------
    | support methods
    |--------------------------------------------------------------------------
    */
    public function createOrder($request, $userId)
    {
        $order_data = order::create([
            'user_id' => $userId,
            'totalPrice' => $request->orderTotalPrice,
            'address' => $request->userAddress,
            'phoneNumber' => CustomerInfo::where('user_id', $userId)->get()[0]->phonenum,
            'status' => 0,
            'payment_method' => $request->orderPaymentMethod,
        ]);

        $order_tracking_data = [
            'user_id'  => $order_data->user_id,
            'order_id' => $order_data->id,
            'status' => 0,
        ];
        orderTracking::create($order_tracking_data);


        $products = [];
        foreach ($request->orderProducts as $product) {
            $products[] = orderProduct::create([
                'product_id' => $product['productId'] + 0,
                'order_id' => $order_data->id,
                'totalCount' => $product['quantity'],
                'totalPrice' => $product['quantity'] * product::find($product['productId'])->price,
            ]);
        }
    }

    private function makeOrderLogic($request)
    {

        $userId = $request->user_id;
        $orderTotalPrice = $request->orderTotalPrice;
        $userAddress = $request->userAddress;
        $orderPaymentMethod = $request->orderPaymentMethod;
        $orderType = $request->order_type;
        $promocodeId = $request->promocode_id;
        $orderProducts = $request->orderProducts;

        $order = [
            'user_id' => $userId,
            'totalPrice' => $orderTotalPrice,
            'address' => $userAddress,
            'phoneNumber' => CustomerInfo::where('user_id', $userId)->value('phonenum'),
            'status' => 0,
            'payment_method' => $orderPaymentMethod,
            'order_type' => $orderType,
        ];

        if ($promocodeId) {
            $order['promo_code_id'] = $promocodeId;
        }

        $orderData = order::create($order);

        // Track order
        orderTracking::create([
            'user_id'  => $orderData->user_id,
            'order_id' => $orderData->id,
            'status' => 0,
        ]);

        // Handle order products
        foreach ($orderProducts as $product) {
            $productModel = product::find($product['productId']);
            $orderProduct = orderProduct::create([
                'product_id' => $productModel->id,
                'order_id' => $orderData->id,
                'totalCount' => $product['quantity'],
                'totalPrice' => $product['quantity'] * $productModel->price,
            ]);

            // 🟢 Handle Addons
            if (!empty($product['addons'])) {

                OrderProductAddsOn::create([
                    'order_product_id' => $orderProduct->id,
                    'adds_on_id' => $product['addons'],
                    'active' => 1
                ]);
            }

            // 🟢 Handle Options + Values
            if (!empty($product['options'])) {
                foreach ($product['options'] as $optionData) {
                    $orderOption = OrderProductOption::create([
                        'order_product_id' => $orderProduct->id,
                        'option_id' => $optionData['option_id'],
                    ]);

                    // values (array of option_value_ids)
                    OrderProductOptionValue::create([
                        'order_product_option_id' => $orderOption->id,
                        'option_value_id' => $optionData['values'],
                        'active' => 1
                    ]);
                }
            }
        }

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
                $productInfo = product::find($product->product_id);
                $category = section::find($productInfo->section_id);


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
