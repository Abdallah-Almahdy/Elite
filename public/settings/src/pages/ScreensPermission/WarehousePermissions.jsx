import useScreenSettingsPreferenceLogic from "../../hooks/settings/useScreenSettingsPreferenceLogic";
import PermissionToggle from "../../components/settings/user/PermissionSettings/PermissionToggle";
import { FaRegCheckCircle, FaSlidersH } from "react-icons/fa";
import { useInvoiceSettings } from "../../contexts/InvoiceSettingsContext";
import { useEffect, useState } from "react";
import WareHouseNamePermissionsCard from "../../components/ui/common/WareHouseNamePermissionsCard";
import WarehouseSettings from "../../components/settings/screens/WarehouseSettings";
import { useScreensPermissions } from "../../contexts/ScreensPermissionsContext";

export default function WarehousePermissions() {
  const { screenSettings } = useScreensPermissions();

  // Extract state values directly from context to guarantee object reliability
  const allowedWareHouseName = screenSettings?.allowedWareHouseName || [];
  const defaultId = allowedWareHouseName[0]?.id || 1;

  const [viewablePermissions, setViewablePermissions] = useState([defaultId]);
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
        <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                      <div className="flex items-center gap-2 mb-4 text-slate-800 font-bold border-b border-slate-100 pb-3">
                        <FaSlidersH className="text-blue-600" />
                        <h2>تخصيص صلاحيات العمليات الداخلية</h2>
                      </div>
                      <WareHouseNamePermissionsCard
                        allowedWareHouseName={allowedWareHouseName}
                        viewablePermissions={viewablePermissions}
                        setViewablePermissions={setViewablePermissions}
                      />
                    </div>
      </div>
    </div>
  );
}
