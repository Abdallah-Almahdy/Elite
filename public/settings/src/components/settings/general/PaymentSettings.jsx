import React from "react";
import { BsCreditCardFill } from "react-icons/bs";
import { useSettingsPreference } from "../../../contexts/SettingsPreferenceContext";

export default function PaymentSettings() {
  const paymentMethods = ["كاش", "بطاقة ائتمان", "انستا باى", "اجل", "محفظة"];
  const {
    defaultPaymentMethod,
    setDefaultPaymentMethod,
    allowedPaymentMethods,
    setAllowedPaymentMethods,
  } = useSettingsPreference();

  return (
    <div className="w-full bg-white rounded-lg">
      <div className="border-b flex items-center">
        <BsCreditCardFill className="text-2xl text-blue-700 mr-5" />
        <h1 className=" p-5 pr-2 font-semibold text-xl">
          اعدادات الدفع الافتراضية
        </h1>
      </div>
      <div className="flex justify-between items-center p-3 text-lg">
        <h2>طريقة الدفع الافتراضية :</h2>
        <div className="w-[50%]">
          <select
            className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
            value={defaultPaymentMethod}
            onChange={(e) => {
              const value = e.target.value;
              setDefaultPaymentMethod(value);
            }}
          >
            <option value="" disabled className="bg-white">
              اختر طريقة الدفع
            </option>
            {paymentMethods.map((method) => (
              <option key={method} value={method} className="bg-white">
                {method}
              </option>
            ))}
          </select>
        </div>
        {/* <p className='text-gray-500 text-base italic'>الطريقة الافتراضيه للفواتير الجديدة</p> */}
      </div>
      <hr className="w-[95%]" />
      <div className="flex justify-between items-center p-3 text-lg">
        <h2>أنواع الدفع المستخدمة :</h2>
        <div className="flex sm:w-[60%] justify-around items-center text-base">
          {paymentMethods.map((method) => (
            <div className="flex items-center me-4" key={method}>
              <input
                id={`allowed-${method}`}
                type="checkbox"
                className="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                name="allowedInvoiceType"
                value={method}
                checked={allowedPaymentMethods.includes(method)}
                onChange={(e) => {
                  const value = e.target.value;
                  setAllowedPaymentMethods((prev) => {
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
