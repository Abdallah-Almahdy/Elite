import React from "react";
import PermissionToggle from "./PermissionToggle";
import { MdOutlinePriceChange } from "react-icons/md";
import { BiSolidDiscount } from "react-icons/bi";
import { useUserSettingsPreference } from "../../../../contexts/UserSettingsPreferenceContext";

export default function DiscountPermission() {
  const { priceChangeAuth, discountAuth, setPriceChangeAuth, setDiscountAuth } =
    useUserSettingsPreference();
  return (
    <div>
      <div className="flex justify-between items-center gap-x-10 p-3 pb-0 pt-2 text-lg">
        <div className="flex items-center gap-x-1">
          <MdOutlinePriceChange className="text-xl text-blue-700" />

          <h2>السماح بتغيير السعر</h2>
        </div>
        <div className="pt-2 sm:w-[50%]">
          <PermissionToggle
            value={priceChangeAuth}
            onChange={(val) => setPriceChangeAuth(val)}
          />
        </div>
      </div>
      <div className="flex justify-between items-center gap-x-10 text-lg p-3 py-0">
        <div className="flex items-center gap-x-1">
          <BiSolidDiscount className="text-xl text-blue-700" />

          <h2>السماح بتطبيق تخفيضات </h2>
        </div>
        <div className="pt-2 sm:w-[50%]">
          <PermissionToggle
            value={discountAuth}
            onChange={(val) => setDiscountAuth(val)}
          />
        </div>
      </div>
      <hr className="w-[95%]" />
    </div>
  );
}
