import React, { useEffect } from "react";
import { useContext } from "react";
import { FormDataContext } from "../../../contexts/FormDataContext";
import { useSelector, useDispatch } from "react-redux";
import { fetchConfigs } from "../../../store/reducers/settingSlice";
export default function OrderType({ selectedOrder, setSelectedOrder }) {
  const dispatch = useDispatch();
  const { formData, setFormData } = useContext(FormDataContext);
  // const invoiceSettings = JSON.parse(localStorage.getItem("Invoice Settings"))
  const invoiceSettings = useSelector(
    (state) => state?.setting?.invoiceSettings,
  );
  useEffect(() => {
    dispatch(fetchConfigs());
  }, [dispatch]);
  const paymentMapping = {
    cash: "كاش",
    credit_card: "بطاقة ائتمان",
    instapay: "انستا باى",
    wallet: "محفظة",
    remaining: "اجل",
  };

  const invoiceMapping = {
    take_away: "تيك أواى",
    delvery: "دليفرى",
    hall: "صالة",
  };
  const paymentMethodOptions = [
    { id: 1, code: "cash", label: "كاش" },
    { id: 2, code: "credit_card", label: "بطاقة ائتمان" },
    { id: 3, code: "instapay", label: "انستا باى" },
    { id: 4, code: "wallet", label: "محفظة" },
    { id: 5, code: "remaining", label: "اجل" },
  ];
  const invoiceTypeOptions = [
    { id: 1, code: "take_away", label: "تيك أواى" },
    { id: 2, code: "delvery", label: "دليفرى" },
    { id: 3, code: "hall", label: "صالة" },
  ];

  const allowedInvoiceTypeCodes = invoiceSettings?.allowedInvoiceTypes || [
    "تيك أواى",
  ];
  const allowedInvoiceTypeLabels = invoiceTypeOptions
    .filter((method) => allowedInvoiceTypeCodes.includes(method.code))
    .map((method) => method.label);

  const userSettings = JSON.parse(localStorage.getItem("User Settings"));

  const types = allowedInvoiceTypeLabels || ["تيك أواى"];
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
          {types?.map((type, index) => (
            <li key={index} className="flex-1">
              <button
                disabled={!userSettings?.invoiceTypeChangeAuth}
                onClick={() => handleInvoiceChange(type)}
                className={`
                  w-full py-1.5 transition-all duration-150 
                  ${
                    selectedOrder === type
                      ? "bg-blue-600 text-white font-semibold shadow-inner"
                      : "bg-white text-gray-600 hover:bg-gray-100"
                  }
                `}
              >
                {type}
              </button>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}
