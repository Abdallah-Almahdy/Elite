import OrderRow from "./OrderRow";
import { useProducts } from "../../contexts/ProductsContext";

export default function OrderTable({ quantityRefs }) {
  const { isPopupOpen } = useProducts();
  return (
    <div
      className={`w-full h-[21rem] sm:h-[35rem] lg:h-[21rem] rounded-sm ${!isPopupOpen ? `overflow-y-auto` : `overflow-hidden`}`}
    >
      <table className="w-full text-center">
        <thead className="bg-gray-300">
          <tr>
            <th className="p-1">#</th>
            <th className="p-1">الكود</th>
            <th className="p-1">الاسم</th>
            <th>الكمية</th>
            <th className="p-1">الوحدة</th>
            <th className="p-1">سعر القطعة</th>
            <th className="p-1">الاجمالى</th>
            <th></th>
          </tr>
        </thead>
        <tbody className="border border-gray-300 overflow-auto">
          <OrderRow quantityRefs={quantityRefs} />
        </tbody>
      </table>
    </div>
  );
}
