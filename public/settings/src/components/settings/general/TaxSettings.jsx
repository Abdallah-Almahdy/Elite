import React from "react";
import PermissionToggle from "../user/PermissionSettings/PermissionToggle";
import { HiReceiptTax } from "react-icons/hi";
import { useSettingsPreference } from "../../../contexts/SettingsPreferenceContext";

export default function TaxSettings() {
  const { applyTax, setApplyTax, taxValue, setTaxValue, taxType, setTaxType } =
    useSettingsPreference();
  const taxTypes = ["%", "ج.م"];

  return (
    <div className="w-full bg-white rounded-lg shadow-lg">
      <div className="flex items-center border-b">
        <HiReceiptTax className="text-2xl text-blue-700 mr-5" />
        <h1 className="p-5 pr-2 font-semibold text-xl">اعدادات الضريبة</h1>
      </div>
      <div className="flex items-center justify-between gap-x-10 p-2 text-lg">
        <h2>تطبيق الضريبة</h2>
        <div className="pt-2 sm:w-[70%]">
          <PermissionToggle
            value={applyTax}
            onChange={(val) => setApplyTax(val)}
          />
        </div>
      </div>
      <hr className="w-[95%]" />
      <div className="flex items-center gap-x-10 p-3 text-lg">
        <h2>مقدار الضريبة :</h2>
        <div className="flex items-center">
          <input
            type="text"
            name="taxValue"
            id="taxValue"
            className="border w-[25%] rounded-md rounded-l-none text-center"
            value={taxValue}
            onChange={(e) => {
              const value = e.target.value;
              setTaxValue(value);
            }}
          />
          <select
            name="taxType"
            id="taxType"
            className="border rounded-md rounded-r-none appearance-none w-[20%] text-center"
            value={taxType}
            onChange={(e) => {
              const value = e.target.value;
              setTaxType(value);
            }}
          >
            {taxTypes.map((type) => (
              <option value={type} key={type} className="">
                {type}
              </option>
            ))}
          </select>
        </div>
      </div>
    </div>
  );
}
