import InvoiceType from "../components/settings/general/InvoiceTypeSettings";
import PaymentSettings from "../components/settings/general/PaymentSettings";
import TaxSettings from "../components/settings/general/TaxSettings";
import WarehouseSettings from "../components/settings/general/WarehouseSettings";
import { useInvoiceSettings } from "../contexts/InvoiceSettingsContext";
import { useSettingsPreference } from "../contexts/SettingsPreferenceContext";
import { useEffect, useState } from "react";
import notify from "../hooks/Notification";
import { useDispatch, useSelector } from "react-redux";
import { sendConfigs } from "../store/reducers/settingSlice";
import { fetchAdmin } from "../store/reducers/adminSlice";

export default function SettingsPage() {
  const dispatch = useDispatch();
  const admin = useSelector((state) => state?.admin?.admin);
  const [isInvoiceSettingsOpen, setIsInvoiceSettingsOpen] = useState(false);
  useEffect(() => {
    dispatch(fetchAdmin());
  }, [dispatch]);
  const paymentMapping = {
    كاش: "cash",
    "بطاقة ائتمان": "credit_card",
    "انستا باى": "instapay",
    محفظة: "wallet",
    اجل: "remaining",
  };
  const invoiceMapping = {
    "تيك أواى": "take_away",
    دليفرى: "delvery",
    صالة: "hall",
  };
  const TaxMapping = {
    "%": "%",
    "ج.م": "pound",
  };
  const { updateInvoiceSettings } = useInvoiceSettings();
  const {
    defaultInvoiceType,
    allowedInvoiceType,
    defaultPaymentMethod,
    allowedPaymentMethods,
    defaultWarehouseName,
    applyTax,
    taxValue,
    taxType,
  } = useSettingsPreference();
  const mappedAllowedInvoiceType = allowedInvoiceType.map((type) => {
    return invoiceMapping[type];
  });
  const mappedAllowedPaymentMethods = allowedPaymentMethods.map((type) => {
    return paymentMapping[type];
  });
  const handleSubmit = async () => {
    const payload = {
      defaultInvoiceType: invoiceMapping[defaultInvoiceType],
      allowedInvoiceType: mappedAllowedInvoiceType,
      defaultPaymentMethod: paymentMapping[defaultPaymentMethod],
      allowedPaymentMethods: mappedAllowedPaymentMethods,
      defaultWarehouseName: defaultWarehouseName,
      applyTax: applyTax,
      taxValue: taxValue,
      taxTypes: TaxMapping[taxType],
      user_id: admin?.id,
    };
    try {
      await dispatch(sendConfigs({ invoiceSettings: payload })).unwrap();
      updateInvoiceSettings(payload);
      notify("تم حفظ الاعدادات بنجاح", "success");
    } catch (err) {
      notify("حدثت مشكلة برجاء المحاولة مرة أخرى", "error");
    }
  };

  // if(!isOpen) return null;
  return (
    <div className="inset-0 z-50 bg-gray-200 w-full min-h-screen flex flex-col mx-auto lg:flex-row p-2 gap-x-10 pt-7">
      {/* Right Section (General Settings) */}
      <div className="w-full md:w-[90%] lg:w-[60%] mx-auto  flex flex-col gap-y-3">
        <h1 className="text-center font-bold text-2xl pb-3">
          اعدادات الفاتورة العامة
        </h1>
        <InvoiceType />
        <PaymentSettings />
        <WarehouseSettings />
        <TaxSettings />
        <div className="w-full flex justify-end mt-10 md:mt-5 lg:mt-7 pl-5">
          <button
            className=" bg-blue-700 hover:bg-blue-500 text-white rounded px-2 py-1.5"
            onClick={() => {
              handleSubmit();
            }}
          >
            {" "}
            حفظ الاعدادات
          </button>
        </div>
      </div>
    </div>
  );
}
