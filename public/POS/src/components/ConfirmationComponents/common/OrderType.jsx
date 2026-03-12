import React, { useEffect } from "react";
import { useContext } from "react";
import { FormDataContext } from "../../../contexts/FormDataContext";
export default function OrderType({ selectedOrder, setSelectedOrder }) {
  const { formData, setFormData } = useContext(FormDataContext);
  const types = [
    { id: 1, name: "تيك أواى" },
    { id: 2, name: "صالة" },
    { id: 3, name: "دليفرى" },
  ];
  const handleInvoiceChange = (value) => {
    setSelectedOrder(value);
    setFormData({ ...formData, invoiceType: value });
  };

  useEffect(() => {
    setSelectedOrder(formData.invoiceType);
  }, []);

  return (
    <div className="w-full">
      <h1 className="text-base font-semibold pr-5 pt-1 text-start">
        نوع الطلب
      </h1>

      <div className="w-[55%] mx-auto mt-2">
        <ul className="w-full flex text-[13px] border border-gray-300 rounded-xl overflow-hidden shadow-sm">
          {types.map((type) => (
            <li key={type.id} className="flex-1">
              <button
                onClick={() => handleInvoiceChange(type?.name)}
                className={`
                  w-full py-1.5 transition-all duration-150 
                  ${
                    selectedOrder === type.name
                      ? "bg-blue-600 text-white font-semibold shadow-inner"
                      : "bg-white text-gray-600 hover:bg-gray-100"
                  }
                `}
              >
                {type.name}
              </button>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}
