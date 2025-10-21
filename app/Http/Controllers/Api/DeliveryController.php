<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeliveryRequest;
use App\Http\Resources\deliveryRecourse;
use App\Models\CustomerInfo;
use App\Models\Delivery;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{

    use  ApiTrait;

    private $firstCustomerAddress = 1;
    private $secondCustomerAddress = 2;


    public function getAllDeliveryPlaces()
    {
        $data = Delivery::all();

        if ($data->isEmpty()) {
            return [];
        }

        return $this->successCollection(deliveryRecourse::class, $data);
    }

    public function getDeliveryPriceByUserID(DeliveryRequest $request)
    {

        $userId = $request->user()->id;
        $addressNo = $request->query('address_no');

        $this->checkExists(CustomerInfo::class, 'user_id', $userId);
        $customer_info = CustomerInfo::where('user_id', $userId)->first();

        if (!ctype_digit($addressNo)) {
            return $this->notFound('this id refers to a non integer value in the customer info table');
        }

        $addresses = [
            $this->firstCustomerAddress => $customer_info->addressCountry,
            $this->secondCustomerAddress => $customer_info->addressCountry2,
        ];

        foreach ($addresses as $key => $deliveryId) {
            if ($addressNo == $key) {
                return $this->getDeliveryPriceForAddress($deliveryId);
            }
        }
        return $this->notFound('Invalid address_no provided');
    }

    private function getDeliveryPriceForAddress($deliveryId)
    {

        if (!$deliveryId) {
            return $this->notFound('No delivery address found for this user');
        }
        $this->checkExists(Delivery::class, 'id', $deliveryId);
        $delivery = Delivery::findOrFail((int) $deliveryId);

        if ($delivery->is_free) {
            return $this->success(['delivery_price' => 0]);
        }

        return $this->success(['delivery_price' => $delivery->delivery_price]);
    }
public function getDeliveryPrice(Request $request)
{
    $id = $request->query('deliveryID');

    if (!$id) {
        return response()->json([
            'error' => 'معرّف التوصيل (id) مطلوب'
        ], 400);
    }

    $delivery = Delivery::find($id);

    if (!$delivery) {
        return response()->json([
            'error' => 'لم يتم العثور على بيانات التوصيل لهذا المعرّف'
        ], 404);
    }

    return response()->json([
        'price' => $delivery->delivery_price
    ]);
}
}
