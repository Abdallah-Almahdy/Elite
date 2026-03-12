import React from "react";
import OrderActions from "./OrderActions";
import { calculateTotals } from "../../utils/calculateTotals";
import { useSelectedProducts } from "../../contexts/SelectedProductsContext";

export default function OrderSummary() {
  const { selectedProducts } = useSelectedProducts();
  const { subtotal, tax, total } = calculateTotals(selectedProducts);
  const discountPercentage = 0;
  const discountValue = 0;
  const deliveryService = 0;
  const dineInService = 0;

  return (
    <div className="w-full border-t border-gray-300 py-2 lg:pt-0  lg:pb-1">
      <div className="flex justify-between items-center">
        <table className="w-full text-sm">
          <tbody>
            {/* خصم نسبة % */}
            <tr className="font-normal">
              <td>خصم نسبة (%)</td>
              <td className="text-end pl-5">
                {discountPercentage.toFixed(1)}%
              </td>

              {/* خصم قيمة */}

              <td>خصم قيمة</td>
              <td className="text-end pl-5">{discountValue.toFixed(2)}</td>
            </tr>

            {/* خدمة التوصيل */}
            <tr className="font-normal">
              <td>خدمة التوصيل</td>
              <td className="text-end pl-5">{deliveryService.toFixed(2)}</td>

              {/* خدمة الصالة */}

              <td>خدمة الصالة</td>
              <td className="text-end pl-5">{dineInService.toFixed(2)}</td>
            </tr>

            {/* الإجمالي */}
            <tr className="font-normal pt-2">
              <td colSpan={2}>الإجمالى</td>
              <td className="text-end pl-5" colSpan={2}>
                {subtotal.toFixed(2)}
              </td>
            </tr>

            {/* القيمة المضافة */}
            <tr className="font-normal">
              <td colSpan={2}>القيمة المضافة (14%)</td>
              <td className="text-end pl-5" colSpan={2}>
                {tax.toFixed(2)}
              </td>
            </tr>

            {/* المبلغ المستحق */}
            <tr className="font-bold">
              <td colSpan={2}>المبلغ المستحق</td>
              <td className="text-end pl-5" colSpan={2}>
                {total.toFixed(2)}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <hr className="mt-2" />

      <OrderActions />
    </div>
  );
}
