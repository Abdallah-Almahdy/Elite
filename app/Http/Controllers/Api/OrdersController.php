<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use App\Services\API\OrdersService;
use App\Services\API\PromocodeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

        $validator = Validator::make($request->all(), [
            'userAddress' => 'required|integer',
            'orderPaymentMethod' => 'required|in:0,1',
            'promocode_id' => 'nullable|integer|exists:promo_codes,id',
            'orderProducts' => 'required|array|min:1',
            'orderProducts.*.productId' => 'required|integer|exists:products,id',
            'orderProducts.*.quantity' => 'required|integer|min:1',
            'orderProducts.*.options' => 'nullable|array',
            'orderProducts.*.options.*.optionId' => 'required_with:orderProducts.*.options|integer|exists:options,id',
            'orderProducts.*.options.*.valueId' => 'required_with:orderProducts.*.options|integer|exists:options_values,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validator->after(function ($validator) use ($request) {
            foreach ($request->orderProducts as $index => $orderProduct) {
                $productId = $orderProduct['productId'];

                // ✅ Validate options belong to the product
                if (!empty($orderProduct['options'])) {
                    foreach ($orderProduct['options'] as $optIndex => $option) {
                        $optionId = $option['optionId'];
                        $valueId  = $option['valueId'];

                        $productHasOption = DB::table('product_options')
                            ->where('product_id', $productId)
                            ->where('option_id', $optionId)
                            ->exists();

                        if (!$productHasOption) {
                            $validator->errors()->add(
                                "orderProducts.$index.options.$optIndex.optionId",
                                "Option ID $optionId does not belong to Product ID $productId."
                            );
                        }

                        // ✅ Validate value belongs to option
                        $valueBelongsToOption = DB::table('options_values')
                            ->where('id', $valueId)
                            ->where('option_id', $optionId)
                            ->exists();

                        if (!$valueBelongsToOption) {
                            $validator->errors()->add(
                                "orderProducts.$index.options.$optIndex.valueId",
                                "Value ID $valueId does not belong to Option ID $optionId."
                            );
                        }
                    }
                }
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $userId = $request->user()->id;

        $promocodeId = $request->promocode_id;

        return  $this->ordersService->makeOrder($request, $userId, $promocodeId);
    }

    public function getAllOrders(Request $request)
    {

        $user = $request->user();

        $userOrdersData =  $this->ordersService->getAllUserOrders($user);

        return $this->success($userOrdersData, 'ordered fetched successfully');
    }

    public function cancelOrder(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$request->user()->orders()->where('id', $orderId)->exists())
        {
            return response()->json(
            [
                "unauthorized"
            ], 403);
        }


        $this->ordersService->cancelOrder($orderId);

        return $this->success([], 'order canceled');
    }
}
