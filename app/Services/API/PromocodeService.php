<?php

namespace App\Services\API;

use App\Http\Requests\Api\CheckPromocodeRequest;
use App\Models\PromoCode;
use App\Models\product;
use App\Models\promo_code_products;
use App\Models\PromoCodeUser;
use Illuminate\Http\Request;

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
    public function checkPromocode(Request $request, $orderTotal)
    {
        $promoCodeID = $request->input('promocode_id');
        $userId = $request->user()->id;
        $products = $request->input('orderProducts');


        $promo = PromoCode::find($promoCodeID);

        // ✅ تحقق من صلاحية الكود
        if (!$promo || !$promo->active || $promo->expiry_date < now()) {
            return $this->notFound('كود غير صالح أو منتهي الصلاحية');
        }

        // ✅ الكود لا يعمل مع المنتجات التي عليها عروض
        if ($promo->check_offer_rate == 0) {
            foreach ($products as $product) {
                $dbProduct = Product::find($product['productId']);
                if ($dbProduct && $dbProduct->offer_rate > 0) {
                    return $this->notFound('هذا الكود لا يمكن استخدامه مع منتج به عرض');
                }
            }
        }

        // ✅ الكود لمستخدم محدد فقط
        if ($promo->user_id !== null && $promo->user_id !== (int)$userId) {
            return $this->notFound('هذا الكود لا يصلح لهذا المستخدم');
        }

        // ✅ الحد الأدنى لقيمة الطلب
        if ($promo->min_order_value && $orderTotal < $promo->min_order_value) {
            return $this->notFound('إجمالي الطلب أقل من الحد الأدنى لاستخدام الخصم');
        }

        // ✅ تحقق من عدد الأكواد المتبقية
        if (!is_null($promo->available_codes) && $promo->available_codes <= 0) {
            return $this->notFound('تم الوصول للحد الأقصى لاستخدام هذا الكود');
        }

        // ✅ تحقق من أن المستخدم لم يستخدم الكود مسبقًا
        $hasUsed = PromoCodeUser::where('user_id', $userId)
            ->where('promo_id', $promo->id)
            ->exists();

        if ($hasUsed) {
            return $this->notFound('لقد استخدمت هذا الكود من قبل');
        }

        // ✅ احصل على المنتجات المسموح بها (لو الكود مربوط بمنتجات معينة)
        $allowedProducts = promo_code_products::where('promo_code_id', $promoCodeID)
            ->pluck('product_id')
            ->toArray();

        $discountTotal = 0;

        if (count($allowedProducts) > 0) {
            // ✅ الكود خاص بمنتجات محددة فقط
            foreach ($products as $product) {
                if (in_array($product['productId'], $allowedProducts)) {
                    $dbProduct = Product::find($product['productId']);
                    if (!$dbProduct) continue;

                    $price = $dbProduct->price;
                    $quantity = $product['quantity'] ?? 1;

                    if ($promo->discount_type === 'percentage') {
                        $discount = $price * ($promo->discount_percentage_value / 100);
                    } else {
                        $discount = $promo->discount_cash_value;
                    }

                    $discountTotal += ($discount * $quantity);
                }
            }

            if ($discountTotal <= 0) {
                return $this->notFound('المنتجات في الطلب لا تشمل أي منتج ينطبق عليه هذا الكود');
            }

            $finalTotal = $orderTotal - $discountTotal;
        } else {
            // ✅ الكود ينطبق على الطلب بالكامل
            if ($promo->discount_type === 'percentage') {
                $discountTotal = $orderTotal * ($promo->discount_percentage_value / 100);
            } else {
                $discountTotal = $promo->discount_cash_value;
            }

            $finalTotal = $orderTotal - $discountTotal;
        }

        // ✅ تحديث بيانات الكود بعد الاستخدام
        if ($promo->promo_cat == 'user') {
            $promo->available_codes = max(0, $promo->available_codes - 1);
            $promo->active = 0;
        } else {
            $promo->available_codes = max(0, $promo->available_codes - 1);
            if ($promo->available_codes <= 0) {
                $promo->active = 0;
            }
        }

        $promo->save();

        return $this->success([
            'promo' => $promo,
            'discount' => round($discountTotal, 2),
            'final_total' => max(0, round($finalTotal, 2)),
        ], 'تم تطبيق الخصم بنجاح');
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
        // ✅ تأكد إن الطلب فعلاً فيه بروموكود
        if (!$order->promo_code_id) {
            return;
        }

        $promoCodeId = $order->promo_code_id;
        $orderUserId = $order->user_id;

        // ✅ تأكد إن البروموكود موجود
        $this->checkExists(PromoCode::class, 'id', $promoCodeId);

        $promocode = PromoCode::find($promoCodeId);
        if (!$promocode) {
            return;
        }

        // ✅ بيانات البروموكود
        $promocodeCategory = $promocode->promo_cat;  // ممكن تكون 'user' أو 'global'
        $promocodeUserId = $promocode->user_id;

        // ✅ لو المستخدم هو المالك في كود user
        if ($promocodeCategory === 'user') {
            if ($promocodeUserId == $orderUserId) {
                $this->incrementPromocodeUsesLeft($promocode);
            }
        } else {
            // ✅ الكود العام
            $this->incrementPromocodeUsesLeft($promocode);
        }

        // ✅ حذف سجل استخدام البروموكود في جدول PromoCodeUser
        PromoCodeUser::where('order_id', $order->id)
            ->where('user_id', $orderUserId)
            ->where('promo_id', $promoCodeId)
            ->delete();
    }

    /**
     * ✅ وظيفة مساعدة لزيادة عدد الاستخدامات المتبقية
     */
    private function incrementPromocodeUsesLeft(PromoCode $promocode)
    {
        if (!is_null($promocode->available_codes)) {
            $promocode->available_codes = $promocode->available_codes + 1;
        }

        // ✅ لو الكود كان غير مفعل وتمت إعادة تنشيطه
        if ($promocode->available_codes > 0 && $promocode->active == 0) {
            $promocode->active = 1;
        }

        $promocode->save();
    }
}
