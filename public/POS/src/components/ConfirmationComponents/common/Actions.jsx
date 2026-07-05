import React, { useEffect } from "react";
import notify from "../../../hooks/Notification";
import { useSelector, useDispatch } from "react-redux";
import { fetchUserPrintersConfig } from "../../../store/reducers/settingSlice";

export default function Actions({
  handleSubmitWithPrinting,
  handleSubmitWithoutPrinting,
}) {
  // const {saveNoPrintAuth} = useUserSettingsPreference();
  // const userSettings = JSON.parse(localStorage.getItem("User Settings"));
  const permissions = useSelector((state)=> state?.setting?.permissions)
  const loading = useSelector((state) => state.order.loading);
  const dispatch = useDispatch()

  useEffect(()=>{
    dispatch(fetchUserPrintersConfig());
  }, [dispatch])
  
  return (
    <div className="w-[90%] mx-auto flex justify-between items-center gap-x-3">
      <button
        onClick={handleSubmitWithPrinting}
        type="button"
        disabled={loading}
        className="text-white w-full mt-3 bg-blue-700 hover:bg-blue-600 bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium hover:shadow-lg transition hover:-translate-y-0.5 font-medium leading-5 rounded-lg text-sm px-4 py-2.5 focus:outline-none"
      >
        {loading ? <span className="loader"></span> : ` حفظ وطباعة`}
      </button>

      {permissions["pos.saveNoPrintAuth"] && (
        <button
          disabled={loading}
          onClick={() => {
            if (!permissions["pos.saveNoPrintAuth"]) {
              notify("لا توجد صلاحيات لهذا الاجراء", "warn");
            } else {
              handleSubmitWithoutPrinting();
            }
          }}
          type="button"
          className="text-blue-700 w-full mt-3 bg-white  bg-brand box-border border border-blue-700 hover:border-blue-600 hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium hover:shadow-lg transition hover:-translate-y-0.5 font-medium leading-5 rounded-lg text-sm px-4 py-2.5 focus:outline-none"
        >
          {loading ? <span className="loader"></span> : `حفظ فقط `}
        </button>
      )}
    </div>
  );
}
