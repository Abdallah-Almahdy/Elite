import React, { useState, useEffect } from "react";
import { BsPrinterFill } from "react-icons/bs";
import { usePrinterSettingsPreference } from "../../../contexts/PrinterSettingsContext";
import PermissionToggle from "../user/PermissionSettings/PermissionToggle";
import { MdOutlinePrintDisabled } from "react-icons/md";
import { getPrinters } from "../../../services/qzService";

export default function PrinterSettings({ availablePrinters }) {
  const {
    printerName,
    setPrinterName,
    errors,
    setErrors,
    saveNoPrintAuth,
    setSaveNoPrintAuth,
  } = usePrinterSettingsPreference();

  return (
    <div className="w-full">
      <div className="flex items-center border-b">
        <BsPrinterFill className="text-2xl text-blue-700 mr-5" />
        <h1 className=" p-5 pr-2 font-semibold text-xl">
          اعدادات طابعة الكاشير{" "}
        </h1>
      </div>
      <div className="flex items-center gap-x-10 p-3 text-lg py-5">
        <h2>اسم الطابعة :</h2>
        <div className="w-[50%]">
          <select
            className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
            value={printerName}
            onChange={(e) => {
              const value = e.target.value;
              setPrinterName(value);
            }}
          >
            <option value="" disabled className="bg-white">
              اختر اسم الطابعة للكاشير
            </option>
            {availablePrinters?.map((method) => (
              <option key={method} value={method} className="bg-white">
                {method}
              </option>
            ))}
          </select>
          {errors.printerName && (
            <p className="text-red-500 text-sm font-bold">
              {errors.printerName}
            </p>
          )}
        </div>
      </div>
      <div className="flex justify-between items-center gap-x-10 text-lg p-3 pt-0">
        <div className="flex items-center gap-x-1">
          <MdOutlinePrintDisabled className="text-xl text-blue-700" />
          <h2>السماح بالحفظ بدون طباعة</h2>
        </div>
        <div className="pt-2 sm:w-[50%]">
          <PermissionToggle
            value={saveNoPrintAuth}
            onChange={(val) => setSaveNoPrintAuth(val)}
          />
        </div>
      </div>
      <hr className="w-[95%]" />
    </div>
  );
}
