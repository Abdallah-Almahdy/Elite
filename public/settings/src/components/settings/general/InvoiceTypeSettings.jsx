import React from "react";
import { PiInvoiceBold } from "react-icons/pi";
import { useSettingsPreference } from "../../../contexts/SettingsPreferenceContext";

export default function InvoiceTypeSettings() {
  const paymentMethods = ["تيك أواى", "دليفرى", "صالة"];
  const {
    defaultInvoiceType,
    setDefaultInvoiceType,
    allowedInvoiceType,
    setAllowedInvoiceType,
  } = useSettingsPreference();

  return (
    <div className="w-full bg-white rounded-lg shadow-lg">
      <div className="flex items-center border-b">
        <PiInvoiceBold className="text-2xl text-blue-700 mr-5" />
        <h1 className=" p-5 pr-2 font-semibold text-xl">
          اعدادات الفاتورة الافتراضية
        </h1>
      </div>
      <div className="flex justify-between items-center p-3 text-lg">
        <h2>نوع الفاتورة الافتراضى :</h2>
        <div className="w-[50%]">
          <select
            className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
            value={defaultInvoiceType}
            // onChange={(e) =>
            //   formik.setFieldValue("paymentMethod", e.target.value)
            // }
            onChange={(e) => {
              const value = e.target.value;
              setDefaultInvoiceType(value);
            }}
          >
            <option value="" disabled className="bg-white">
              اختر نوع الفاتورة
            </option>
            {paymentMethods.map((method) => (
              <option key={method} value={method} className="bg-white">
                {method}
              </option>
            ))}
          </select>
        </div>
      </div>
      <hr className="w-[95%]" />
      <div className="flex justify-between items-center p-3 text-lg">
        <h2>أنواع الفاتورة المستخدمة :</h2>
        <div className="flex sm:w-[50%] justify-around items-center text-base">
          {paymentMethods.map((method) => (
            <div className="flex items-center me-4" key={method}>
              <input
                id={`allowed-${method}`}
                type="checkbox"
                className="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft accent-blue-700"
                name="allowedInvoiceType"
                value={method}
                checked={allowedInvoiceType.includes(method)}
                onChange={(e) => {
                  const value = e.target.value;
                  setAllowedInvoiceType((prev) => {
                    if (e.target.checked) {
                      return [...prev, value];
                    } else {
                      return prev.filter((ele) => ele !== value);
                    }
                  });
                }}
              />
              <label
                // htmlFor={method}
                className="select-none ms-2 text-sm font-medium text-heading"
              >
                {method}
              </label>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
