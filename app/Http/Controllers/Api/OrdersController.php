<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use App\Services\API\OrdersService;
use App\Services\API\PromocodeService;

class OrdersController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | IOC
    |--------------------------------------------------------------------------
    */

    use ApiTrait;

    protected $promocodeService;
    protected $ordersService;

    public function __construct(
        PromocodeService $promocodeService,
        OrdersService $ordersService
    ) {
        $this->promocodeService = $promocodeService;
        $this->ordersService = $ordersService;
    }

    /*
    |--------------------------------------------------------------------------
    | Main methods
    |--------------------------------------------------------------------------
    */

    public function createOrder(Request $request)
    {
        $userId = $request->user()->id;
        $promocodeId = $request->promocode_id;

        $this->ordersService->createOrder($request, $userId, $promocodeId);

        return $this->success([], 'Order created successfully');
    }

    public function getAllOrders(Request $request)
    {

        $userId = $request->user()->id;

        $userOrdersData =  $this->ordersService->getAllUserOrders($userId, $request);

        return $this->success($userOrdersData, 'ordered fetched successfully');
    }

    public function cancelOrder(Request $request)
    {
        $orderId = $request->query('order_id');

        $this->ordersService->cancelOrder($orderId);

        return $this->success([], 'order canceled');
    }
}
