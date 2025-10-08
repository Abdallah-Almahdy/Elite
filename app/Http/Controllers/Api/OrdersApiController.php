<?php

namespace App\Http\Controllers\api;

use App\Models\User;
// use App\Models\Order;
// use App\Models\Product;
// use App\Models\Section;
// use App\Models\OrderProduct;
// use Illuminate\Http\Request;
//use App\Models\CustomerInfo;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Services\API\OrdersService;
use App\Http\Controllers\Controller;

class OrdersApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | IOC
    |--------------------------------------------------------------------------
    */

    use ApiTrait;
    protected  $ordersService;

    public function __construct(OrdersService $ordersService)
    {
        $this->ordersService = $ordersService;
    }

    /*
    |--------------------------------------------------------------------------
    | Main methods
    |--------------------------------------------------------------------------
    */

    public function createOrder(Request $request)
    {
        $userId = $request->user_id;

        $this->checkExists(User::class, 'id', $userId);

        $this->ordersService->createOrder($request, $userId);

        return $this->success([], 'order done');
    }


    public function getAllOrders(Request $request)
    {
        $userId = $request->query('user_id');

        $userOrdersData =  $this->ordersService->getAllUserOrders($userId, $request);

        return $this->success($userOrdersData, 'ordered fetched successfully');
    }
}
