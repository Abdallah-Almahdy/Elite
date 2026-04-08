import { BsPrinterFill } from "react-icons/bs";
import { usePrinterSettingsPreference } from "../../../contexts/PrinterSettingsContext";
import { IoIosArrowDown } from "react-icons/io";

export default function InvoicePrinterSettings({ availablePrinters }) {
  const {
    receiptPrinterName,
    invoicePrinterName,
    receiptPrintAuth,
    invoicePrintAuth,
    receiptModelName,
    invoiceModelName,
    receiptNum,
    invoiceNum,
    setReceiptPrinterName,
    setInvoicePrinterName,
    setReceiptPrintAuth,
    setInvoicePrintAuth,
    setReceiptModelName,
    setInvoiceModelName,
    setReceiptNum,
    setInvoiceNum,
  } = usePrinterSettingsPreference();
  const decrementReceiptNum = (prev) => {
    const max = Math.max(1, --prev);
    setReceiptNum(max);
  };
  const incrementReceiptNum = (prev) => {
    setReceiptNum(++prev);
  };
  const decrementInvoiceNum = (prev) => {
    const max = Math.max(1, --prev);
    setInvoiceNum(max);
  };
  const incrementInvoiceNum = (prev) => {
    setInvoiceNum(++prev);
  };
  const handleChangeReceiptNum = (val) => {
    const max = Math.max(1, val);
    setReceiptNum(max);
  };
  const handleChangeInvoiceNum = (val) => {
    const max = Math.max(1, val);
    setInvoiceNum(max);
  };
  return (
    <div className="w-full">
      <div className="flex items-center border-b">
        <BsPrinterFill className="text-2xl text-blue-700 mr-5" />
        <h1 className=" p-5 pr-2 font-semibold text-xl">
          اعدادات طابعة الفاتورة{" "}
        </h1>
      </div>
      {/* <div className='flex items-center gap-x-10 p-3 text-lg py-5'>
        <h2>اسم الطابعة :</h2>
        <div className='w-[50%] relative'>
            

              <select
                  className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
                  value={barcodePrinterName}
                  
                  onChange={(e) => {
  const value = e.target.value;
  setBarcodePrinterName(value)
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
      {/* </div> */}
      {/* </div>  */}
      <table className="w-full">
        <thead>
          <tr>
            <th>اسم الصلاحية</th>
            <th>اسم النموذج</th>
            <th>عدد النسخ</th>
            <th>اسم الطابعة</th>
          </tr>
        </thead>
        <tbody className="w-full">
          <tr className="border-b ">
            <td className="lg:px-3">
              <div className="pb-3 pt-2 flex justify-center items-center w-full mx-auto">
                <div className="flex items-center me-4">
                  <input
                    id="inline-checkbox"
                    type="checkbox"
                    defaultValue=""
                    checked={receiptPrintAuth}
                    onChange={(e) => {
                      setReceiptPrintAuth(e.target.checked);
                    }}
                    className="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                  />
                  <label
                    htmlFor="inline-checkbox"
                    className="select-none ms-2 text-base  text-heading"
                  >
                    طباعة ايصال
                  </label>
                </div>
              </div>
            </td>
            <td>
              <div className="w-[95%] lg:w-[65%] relative mx-auto">
                <select
                  className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
                  value={receiptModelName}
                  onChange={(e) => {
                    const value = e.target.value;
                    setReceiptModelName(value);
                  }}
                >
                  <option value="" disabled className="bg-white">
                    اختر اسم النموذج
                  </option>
                  {availablePrinters?.map((method) => (
                    <option key={method} value={method} className="bg-white">
                      {method}
                    </option>
                  ))}
                </select>
                <div className="absolute left-1 top-2">
                  <IoIosArrowDown className="text-xl text-gray-400" />
                </div>
                {/* {errors.printerName && (
  <p className="text-red-500 text-sm font-bold">{errors.printerName}</p>
)} */}
              </div>
            </td>
            <td>
              <div className=" flex justify-center items-center relative">
                <button
                  className={`bg-red-600 hover:bg-red-400 rounded-lg rounded-l-none px-1 sm:px-3 absolute right-0 lg:right-[15%] text-white ${receiptNum == 1 ? `cursor-not-allowed` : ` cursor-pointer`}`}
                  onClick={() => decrementReceiptNum(receiptNum)}
                  disabled={receiptNum == 1}
                >
                  -
                </button>
                <input
                  className="w-[70%] lg:w-[50%] text-center border rounded-lg focus:outline-none"
                  type="text"
                  name="receiptNum"
                  id="receiptNum"
                  value={receiptNum}
                  onChange={(e) => handleChangeReceiptNum(e.target.value)}
                />
                <button
                  className="bg-green-600 hover:bg-green-500 rounded-lg rounded-r-none px-1 sm:px-3 absolute left-0 lg:left-[15%] text-white"
                  onClick={() => incrementReceiptNum(receiptNum)}
                >
                  +
                </button>
              </div>
            </td>
            <td>
              <div className="w-[80%] mx-auto relative">
                <select
                  className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
                  value={receiptPrinterName}
                  onChange={(e) => {
                    const value = e.target.value;
                    setReceiptPrinterName(value);
                  }}
                >
                  <option value="" disabled className="bg-white">
                    اختر اسم الطابعة للايصال
                  </option>
                  {availablePrinters?.map((method) => (
                    <option key={method} value={method} className="bg-white">
                      {method}
                    </option>
                  ))}
                </select>
                <div className="absolute left-1 top-2">
                  <IoIosArrowDown className="text-xl text-gray-400" />
                </div>
                {/* {errors.printerName && (
  <p className="text-red-500 text-sm font-bold">{errors.printerName}</p>
)} */}
              </div>
            </td>
          </tr>
          <tr>
            <td className="lg:px-3">
              <div className="pb-3 pt-2 flex justify-center items-center w-full mx-auto">
                <div className="flex items-center me-4">
                  <input
                    id="inline-checkbox"
                    type="checkbox"
                    defaultValue=""
                    checked={invoicePrintAuth}
                    onChange={(e) => {
                      setInvoicePrintAuth(e.target.checked);
                    }}
                    className="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                  />
                  <label
                    htmlFor="inline-checkbox"
                    className="select-none ms-2 text-base  text-heading"
                  >
                    طباعة فاتورة
                  </label>
                </div>
              </div>
            </td>
            <td>
              <div className="w-[95%] lg:w-[65%] relative mx-auto">
                <select
                  className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
                  value={invoiceModelName}
                  onChange={(e) => {
                    const value = e.target.value;
                    setInvoiceModelName(value);
                  }}
                >
                  <option value="" disabled className="bg-white">
                    اختر اسم النموذج
                  </option>
                  {availablePrinters?.map((method) => (
                    <option key={method} value={method} className="bg-white">
                      {method}
                    </option>
                  ))}
                </select>
                <div className="absolute left-1 top-2">
                  <IoIosArrowDown className="text-xl text-gray-400" />
                </div>
                {/* {errors.printerName && (
  <p className="text-red-500 text-sm font-bold">{errors.printerName}</p>
)} */}
              </div>
            </td>
            <td>
              <div className=" flex justify-center items-center relative">
                <button
                  className={`bg-red-600 hover:bg-red-400 rounded-lg rounded-l-none px-1 sm:px-3 absolute right-0 lg:right-[15%] text-white ${invoiceNum == 1 ? `cursor-not-allowed` : ` cursor-pointer`}`}
                  onClick={() => decrementInvoiceNum(invoiceNum)}
                  disabled={invoiceNum == 1}
                >
                  -
                </button>
                <input
                  className="w-[70%] lg:w-[50%] text-center border rounded-lg focus:outline-none"
                  type="text"
                  name="receiptNum"
                  id="receiptNum"
                  value={invoiceNum}
                  onChange={(e) => handleChangeInvoiceNum(e.target.value)}
                />
                <button
                  className="bg-green-600 hover:bg-green-500 rounded-lg rounded-r-none px-1 sm:px-3 absolute left-0 lg:left-[15%] text-white"
                  onClick={() => incrementInvoiceNum(invoiceNum)}
                >
                  +
                </button>
              </div>
            </td>
            <td>
              <div className="w-[80%] mx-auto relative">
                <select
                  className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
                  value={invoicePrinterName}
                  onChange={(e) => {
                    const value = e.target.value;
                    setInvoicePrinterName(value);
                  }}
                >
                  <option value="" disabled className="bg-white">
                    اختر اسم الطابعة للفاتورة
                  </option>
                  {availablePrinters?.map((method) => (
                    <option key={method} value={method} className="bg-white">
                      {method}
                    </option>
                  ))}
                </select>
                <div className="absolute left-1 top-2">
                  <IoIosArrowDown className="text-xl text-gray-400" />
                </div>
                {/* {errors.printerName && (
  <p className="text-red-500 text-sm font-bold">{errors.printerName}</p>
)} */}
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <hr className="w-[95%]" />
    </div>
  );
}
