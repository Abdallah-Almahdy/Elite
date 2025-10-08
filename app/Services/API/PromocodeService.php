<?php

namespace App\Services\API;

use App\Http\Requests\Api\CheckPromocodeRequest;
use App\Models\PromoCode;
use App\Models\product;
use App\Models\PromoCodeUser;

use App\Traits\ApiTrait;

class PromocodeService
{
    /*
    |--------------------------------------------------------------------------
    | IOC
    |--------------------------------------------------------------------------
    */
    use ApiTrait;

    /*
    |--------------------------------------------------------------------------
    | main methods
    |--------------------------------------------------------------------------
    */
    public function checkPromocode(CheckPromocodeRequest $request)
    {
        $promoCodeID = $request->input('promocode_id');
        $userId = $request->input('user_id');
        $orderTotal = $request->input('orderTotalPrice');
        $products = $request->input('orderProducts');

        $promoCodeCode = PromoCode::find($promoCodeID)->code;

        // if exist
        $promoCodeCheck = PromoCode::where('active', true)
            ->where('id', $promoCodeID)
            ->where('expiry_date', '>', now())
            ->where('code', $promoCodeCode) // Ensure you're checking the correct promo code
            ->first();

        if (!$promoCodeCheck) {
            return $this->notFound('كود غير صالح');
        }

        // if it doesnt work with offers
        if ($promoCodeCheck->check_offer_rate == 0) {
            // code
            foreach ($products as $product) {
                if (product::find($product['productId'])->offer_rate > 0) {
                    return $this->notFound('هذا الكود لا يمكن إستخدامة مع منتج به عرض');
                }
            }
        }

        // if it doesnt belong to the user
        if ($promoCodeCheck->user_id !== null && $promoCodeCheck->user_id !== $userId + 0) {
            return $this->notFound('هذا الكود لا يصلح لهذا المستخدم');
        }

        // Find valid promo code
        $promo = PromoCode::where('code', $promoCodeCode)
            ->where('active', true)
            ->where('expiry_date', '>', now())
            ->first();

        if (!$promo) {
            return $this->notFound('كود غير صالح');
        }

        // Check minimum order value
        if ($orderTotal < $promo->min_order_value) {
            return $this->notFound('إجمالي الطلب اقل من الحد الأدني للخصم');
        }


        if ($promoCodeCheck->available_codes != null) {
            // Check available_codes
            if ($promoCodeCheck->available_codes <= 0) {
                return $this->notFound('تم الوصول للحد الأقصي للمستخدمين');
            }
        }


        // Check user usage
        $hasUsed = PromoCodeUser::where('user_id', $userId)
            ->where('promo_id', $promo->id)
            ->exists();

        if ($hasUsed) {
            return $this->notFound('لقد أستخدمت هذا العرض بالفعل');
        }

        if ($promoCodeCheck->promo_cat == 'user') {
            if ($promoCodeCheck->user_id == $userId) {
                $promoCodeCheck->available_codes = $promoCodeCheck->available_codes - 1;
                $promoCodeCheck->active = 0;
                $promoCodeCheck->save();
            }
        } else {
            $promoCodeCheck->available_codes = $promoCodeCheck->available_codes - 1;
            if ($promoCodeCheck->available_codes == 0) {
                $promoCodeCheck->active = 0;
            }
            $promoCodeCheck->save();
        }


        return $this->success($promo,   'تم تطبيق الخصم');
    }

    public function markPromocodeAsUsed($promocodeId, $orderId, $userId)
    {
        if ($promocodeId) {
            PromoCodeUser::create([
                'used' => 1,
                'used_at' => now(),
                'order_id' => $orderId,
                'user_id' => $userId,
                'promo_id' => $promocodeId,
            ]);
        }
    }



    public function restorePromocodeUsage($order)
    {
        $orderId = $order->user_id;;

        $promoCodeId = $order->promo_code_id;
        $orderUserId = $order->user_id;
        $this->checkExists(PromoCode::class, 'id', $promoCodeId);

        $promocode = PromoCode::find($order->promo_code_id);

        // promocode data
        $promocodeCategory = $promocode->promo_cat;
        $promocodeUserId = $promocode->user_id;

        // if the users cancles the order the promocode will be available again
        // and the user will be able to use it again
        // resestPromocode

        if ($promocodeCategory == 'user') {
            if ($promocodeUserId  == $orderUserId) {
                $this->incrementPromocodeUsesLeft($promocode);
            }
        } else {
            $this->incrementPromocodeUsesLeft($promocode);
        }


        PromoCodeUser::where('order_id', $orderId)
            ->where('user_id', $order->user_id)
            ->where('promo_id', $promocode->id)->delete();
    }
    /*
    |--------------------------------------------------------------------------
    | support methods
    |--------------------------------------------------------------------------
    */
    protected function incrementPromocodeUsesLeft($promocode)
    {
        if ($promocode->available_codes == 0) {
            $promocode->active = 1;
        }
        $promocode->available_codes = $promocode->available_codes + 1;
        $promocode->save();
    }
}
