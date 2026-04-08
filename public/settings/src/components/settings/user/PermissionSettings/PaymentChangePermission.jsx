import React from "react";
import PermissionToggle from "./PermissionToggle";
import { FaMoneyBillTransfer } from "react-icons/fa6";
import { useUserSettingsPreference } from "../../../../contexts/UserSettingsPreferenceContext";

export default function PaymentChangePermission() {
  const { methodChangeAuth, setMethodChangeAuth } = useUserSettingsPreference();
  return (
    <div>
      <div className="flex justify-between items-center gap-x-10 text-lg pt-2 px-3">
        <div className="flex items-center gap-x-1">
          <FaMoneyBillTransfer className="text-xl text-blue-700" />
          <h2>السماح بتغيير طريقة الدفع</h2>
        </div>
        <div className="pt-2 sm:w-[50%]">
          <PermissionToggle
            value={methodChangeAuth}
            onChange={(val) => setMethodChangeAuth(val)}
          />
        </div>
      </div>
    </div>
  );
}
