import useScreenSettingsPreferenceLogic from "../../hooks/settings/useScreenSettingsPreferenceLogic";
import PermissionToggle from "../../components/settings/user/PermissionSettings/PermissionToggle";
import { FaRegCheckCircle } from "react-icons/fa";
import { useInvoiceSettings } from "../../contexts/InvoiceSettingsContext";
import { useNavigate } from "react-router-dom";
import notify from "../../hooks/Notification";
import WarehouseSettings from "../../components/settings/screens/WarehouseSettings";
import { useEffect } from "react";

export default function WarehousePermissions() {
  const navigate = useNavigate();
  const { updateScreenSettings } = useInvoiceSettings();
  const {
    WarehouseName,
    setWarehouseName,
    warehouseShow,
    setWarehouseShow,
    warehouseEdit,
    setWarehouseEdit,
    warehouseDelete,
    setWarehouseDelete,
  } = useScreenSettingsPreferenceLogic();

  const settings = [
    {
      label: "السماح بعرض شاشة المخازن",
      value: warehouseShow,
      setter: setWarehouseShow,
    },
    {
      label: "السماح بتعديل شاشة المخازن ",
      value: warehouseEdit,
      setter: setWarehouseEdit,
    },
    {
      label: "السماح بالمسح فى شاشة المخازن ",
      value: warehouseDelete,
      setter: setWarehouseDelete,
    },
  ];

  useEffect(() => {
    const screensSettingSubmit = () => {
      const payload = {
        WarehouseName: WarehouseName,
        warehouseShow: warehouseShow,
        warehouseEdit: warehouseEdit,
        warehouseDelete: warehouseDelete,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    };

    screensSettingSubmit();
  }, [WarehouseName, warehouseShow, warehouseEdit, warehouseDelete]);
  return (
    <div className="min-h-screen bg-gray-100 p-4 lg:p-8 flex flex-col items-center">
      <h1 className="text-2xl font-bold mb-6 text-gray-800">
        إعدادات صلاحيات شاشة المخازن
      </h1>
      <div className="w-[50%] mx-auto mb-7">
        <WarehouseSettings
          WarehouseName={WarehouseName}
          setWarehouseName={setWarehouseName}
        />
      </div>
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
