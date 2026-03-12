import React from "react";
import { calculateTotals } from "../../utils/calculateTotals";
import { useSelectedProducts } from "../../contexts/SelectedProductsContext";
export default function OrderSummaryCard() {
  const { selectedProducts } = useSelectedProducts();
  const { subtotal, tax, total } = calculateTotals(selectedProducts);
  return (
    <div className="w-[95%] mx-auto bg-white flex flex-col px-5 py-2 rounded-lg">
      <div className="flex justify-between items-center mb-2">
        <h1 className="text-xl font-semibold">الفاتورة</h1>
        {/* icon */}
      </div>
      <div>
        <table className="w-full border-separate border-spacing-2">
          <tbody>
            <tr>
              <td className="w-1/2 font-medium text-gray-400">
                الاجمالى الفرعى
              </td>
              <td className="text-end font-medium text-blue-700">
                {subtotal.toFixed(2)} ج.م
              </td>
            </tr>
            <tr>
              <td className="font-medium text-gray-400">رسوم التوصيل</td>
              <td className="text-end font-medium text-blue-700">0 ج.م</td>
            </tr>

            <tr>
              <td className="font-medium text-gray-400">الضريبة14%</td>
              <td className="text-end font-medium text-blue-700">
                {tax.toFixed(2)}
              </td>
            </tr>
            <tr className="">
              <td className="font-medium text-gray-400">رسوم الصالة</td>
              <td className="text-end font-medium text-blue-700">0 ج.م</td>
            </tr>
            <tr>
              <td className="font-medium text-gray-400">الخصم</td>
              <td className="text-end font-medium text-blue-700">0 ج.م</td>
            </tr>
            <tr>
              <td colSpan={2} className="border-y border-gray-300"></td>
            </tr>
            <tr>
              <td className="font-semibold text-lg">الاجمالى</td>
              <td className="text-end text-lg font-semibold text-blue-700">
                {total.toFixed(2)} ج.م
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  );
}
