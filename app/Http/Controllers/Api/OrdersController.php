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

        $request->validate([
            'orderTotalPrice' => 'required|numeric',
            'userAddress' => 'required|integer',
            'orderPaymentMethod' => 'required|in:0,1', // 0 credit 1, cash
            'promocode_id' => 'nullable|integer|exists:promo_codes,id',
            'orderProducts' => 'required|array|min:1',
            'orderProducts.*.productId' => 'required|integer|exists:products,id',
            'orderProducts.*.quantity' => 'required|integer|min:1',
            'orderProducts.*.options' => 'nullable|array',
            'orderProducts.*.options.*.optionId' => 'required_with:orderProducts.*.options|integer|exists:options,id',
            'orderProducts.*.options.*.valueId' => 'required_with:orderProducts.*.options|integer|exists:options_values,id'
            
        ]);


        $userId = $request->user()->id;

        $promocodeId = $request->promocode_id;

        return  $this->ordersService->makeOrder($request, $userId, $promocodeId);
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
