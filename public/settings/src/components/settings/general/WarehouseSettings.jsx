import React, { useEffect } from "react";
import { PiInvoiceBold } from "react-icons/pi";
import useSettingsPreferenceLogic from "../../../hooks/settings/useSettingsPreferenceLogic";
import { useSettingsPreference } from "../../../contexts/SettingsPreferenceContext";
import { useDispatch, useSelector } from "react-redux";
import { fetchWarehouseNames } from "../../../store/reducers/settingSlice";

export default function WarehouseSettings() {
  const warehouseNames = useSelector((state) => state?.setting?.warehouseNames);
  const dispatch = useDispatch();

  // useEffect(() => {
  //   dispatch(fetchWarehouseNames());
  // }, [dispatch]);

  const { defaultWarehouseName, setDefaultWarehouseName } =
    useSettingsPreference();

  return (
    <div className="w-full bg-white rounded-lg shadow-lg">
      <div className="flex items-center border-b">
        <PiInvoiceBold className="text-2xl text-blue-700 mr-5" />
        <h1 className=" p-5 pr-2 font-semibold text-xl">
          اعدادات المخزن الافتراضية
        </h1>
      </div>
      <div className="flex justify-between items-center p-3 text-lg">
        <h2>اسم المخزن الافتراضى :</h2>
        <div className="w-[50%]">
          <select
            className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
            value={defaultWarehouseName}
            // onChange={(e) =>
            //   formik.setFieldValue("paymentMethod", e.target.value)
            // }
            onChange={(e) => {
              const value = e.target.value;
              setDefaultWarehouseName(value);
            }}
          >
            <option value="" disabled className="bg-white">
              اختر اسم المخزن
            </option>
            {warehouseNames.map((method) => (
              <option key={method} value={method} className="bg-white">
                {method}
              </option>
            ))}
          </select>
        </div>
      </div>
    </div>
  );
}
