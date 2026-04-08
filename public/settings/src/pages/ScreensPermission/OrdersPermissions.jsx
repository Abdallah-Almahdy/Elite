import useScreenSettingsPreferenceLogic from "../../hooks/settings/useScreenSettingsPreferenceLogic";
import PermissionToggle from "../../components/settings/user/PermissionSettings/PermissionToggle";
import { FaRegCheckCircle } from "react-icons/fa";
import { useInvoiceSettings } from "../../contexts/InvoiceSettingsContext";
import { useNavigate } from "react-router-dom";
import notify from "../../hooks/Notification";
import { useEffect } from "react";

export default function OrdersPermissions() {
  const navigate = useNavigate();
  const { updateScreenSettings } = useInvoiceSettings();
  const {
    orderShow,
    setOrderShow,
    orderPrepare,
    setOrderPrepare,
    orderCancel,
    setOrderCancel,
    orderFinish,
    setOrderFinish,
    orderShowSidebar,
    setOrderShowSidebar,
    orderShipment,
    setOrderShipment,
  } = useScreenSettingsPreferenceLogic();

  const settings = [
    { label: "السماح بعرض الطلبات   ", value: orderShow, setter: setOrderShow },
    {
      label: "السماح بتجهيز الطلب   ",
      value: orderPrepare,
      setter: setOrderPrepare,
    },
    {
      label: "السماح بالغاء طلب   ",
      value: orderCancel,
      setter: setOrderCancel,
    },
    { label: "السماح بانهاء طلب ", value: orderFinish, setter: setOrderFinish },
    {
      label: "السماح بشحن طلب ",
      value: orderShipment,
      setter: setOrderShipment,
    },
    {
      label: "اظهار الشريط الجانبى",
      value: orderShowSidebar,
      setter: setOrderShowSidebar,
    },
  ];

  useEffect(() => {
    const screensSettingSubmit = () => {
      const payload = {
        orderShow: orderShow,
        orderPrepare: orderPrepare,
        orderCancel: orderCancel,
        orderFinish: orderFinish,
        orderShipment: orderShipment,
        orderShowSidebar: orderShowSidebar,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    };
    screensSettingSubmit();
  }, [
    orderShow,
    orderPrepare,
    orderCancel,
    orderFinish,
    orderShipment,
    orderShowSidebar,
  ]);

  return (
    <div className="min-h-screen bg-gray-100 p-4 lg:p-8 flex flex-col items-center">
      <h1 className="text-2xl font-bold mb-6 text-gray-800">
        إعدادات صلاحيات شاشة الطلبات
      </h1>

      <div className="grid gap-6 w-full lg:w-[70%]">
        {settings.map((setting, idx) => (
          <div
            key={idx}
            className="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 flex justify-between items-center"
          >
            <div className="flex items-center gap-2">
              <FaRegCheckCircle className="text-blue-500 text-xl" />
              <h2 className="text-lg font-medium text-gray-700">
                {setting.label}
              </h2>
            </div>
            <PermissionToggle
              value={setting.value}
              onChange={(val) => setting.setter(val)}
            />
          </div>
        ))}
      </div>
    </div>
  );
}
