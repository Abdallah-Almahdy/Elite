import { BsPrinterFill } from "react-icons/bs";
import { usePrinterSettingsPreference } from "../../../contexts/PrinterSettingsContext";
import PermissionToggle from "../user/PermissionSettings/PermissionToggle";
import { MdOutlinePrintDisabled } from "react-icons/md";
import { IoIosArrowDown } from "react-icons/io";

export default function BarcodePrinterSettings({ availablePrinters }) {
  const { barcodePrinterName, setBarcodePrinterName } =
    usePrinterSettingsPreference();

  return (
    <div className="w-full">
      <div className="flex items-center border-b">
        <BsPrinterFill className="text-2xl text-blue-700 mr-5" />
        <h1 className=" p-5 pr-2 font-semibold text-xl">
          اعدادات طابعة الباركود{" "}
        </h1>
      </div>
      <div className="flex items-center gap-x-10 p-3 text-lg py-5">
        <h2>اسم الطابعة :</h2>
        <div className="w-[50%] relative">
          <select
            className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
            value={barcodePrinterName}
            onChange={(e) => {
              const value = e.target.value;
              setBarcodePrinterName(value);
            }}
          >
            <option value="" disabled className="bg-white">
              اختر اسم الطابعة للباركود
            </option>
            {availablePrinters?.map((method) => (
              <option key={method} value={method} className="bg-white">
                {method}
              </option>
            ))}
          </select>
          <div className="absolute left-2 top-2">
            <IoIosArrowDown className="text-xl text-gray-400" />
          </div>
          {/* {errors.printerName && (
  <p className="text-red-500 text-sm font-bold">{errors.printerName}</p>
)} */}
        </div>
      </div>

      <hr className="w-[95%]" />
    </div>
  );
}
