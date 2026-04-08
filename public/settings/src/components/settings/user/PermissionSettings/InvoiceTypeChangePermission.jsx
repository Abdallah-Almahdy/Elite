import React from "react";
import PermissionToggle from "./PermissionToggle";
import { FaFileInvoiceDollar } from "react-icons/fa6";
import { useUserSettingsPreference } from "../../../../contexts/UserSettingsPreferenceContext";

export default function InvoiceTypeChangePermission() {
  const { invoiceTypeChangeAuth, setInvoiceTypeChangeAuth } =
    useUserSettingsPreference();
  return (
    <div>
      <div className="flex justify-between items-center gap-x-10 text-lg px-3">
        <div className="flex items-center gap-x-1">
          <FaFileInvoiceDollar className="text-xl text-blue-700" />
          <h2>السماح بتغيير نوع الفاتورة </h2>
        </div>
        <div className="pt-2 sm:w-[50%]">
          <PermissionToggle
            value={invoiceTypeChangeAuth}
            onChange={(val) => setInvoiceTypeChangeAuth(val)}
          />
        </div>
      </div>
    </div>
  );
}
