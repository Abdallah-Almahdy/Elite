import { IoCloseSharp } from "react-icons/io5";
import { useScreensPermissions } from "../../../contexts/ScreensPermissionsContext";
import { useDispatch, useSelector } from "react-redux";
import { useEffect } from "react";
import { fetchWarehouseNames } from "../../../store/reducers/settingSlice";

export default function WarehouseSettings({ 
  WarehouseName, 
  setWarehouseName, 
  viewablePermissions,
  setViewablePermissions,
  //setDefaultWarehouseName,
  isUserMode = false 
}) {
  
  const wareHouseNames = useSelector((state)=> state?.setting?.warehouseNames);
  const dispatch = useDispatch()

  const { screenSettings, setScreenSettings } = useScreensPermissions();

  const currentActiveWarehouse = isUserMode && screenSettings ? screenSettings?.WarehouseName : WarehouseName;

  const allowedWareHouseName = isUserMode && screenSettings?.allowedWareHouseName?.length > 0
    ? screenSettings.allowedWareHouseName 
    : (viewablePermissions && viewablePermissions.length > 0
        ? wareHouseNames.filter(w => viewablePermissions.includes(w.id))
        : (currentActiveWarehouse ? wareHouseNames.filter(w => w.name === currentActiveWarehouse) : [])
      );
  
  return (
    <div className="w-full bg-white rounded-3xl" dir="rtl">
      <div className="flex items-center gap-x-10 p-3 text-lg py-5">
        <h2>اسم المخزن الافتراضى:</h2>
        <div className="relative w-[50%]">
          <select
            className="border rounded-xl w-full placeholder:text-ellipsis placeholder:text-base px-1 focus:outline-blue-500 py-1 appearance-none"
            value={WarehouseName || ""}
            onChange={(e) => {
              const value = e.target.value;
              setWarehouseName(Number(value));

              const selectedWarehouse = wareHouseNames.find(
                (method) => method.id === Number(value)
              );

              if (selectedWarehouse) {
                setViewablePermissions((prev) =>
                  prev?.includes(selectedWarehouse.id) ? prev : [...(prev || []), selectedWarehouse.id]
                );
                
                if (isUserMode) {
                  setScreenSettings((prev) => {
                    const currentAllowed = prev?.allowedWareHouseName || [];
                    const alreadyExists = currentAllowed.some(item => item.id === selectedWarehouse.id);
                    
                    const updatedAllowed = alreadyExists 
                      ? currentAllowed 
                      : [...currentAllowed, { id: selectedWarehouse.id, name: selectedWarehouse.name, is_default: true }];

                    const currentPermissions = prev?.warehousePermissions || [];
                    const permExists = currentPermissions.some(p => p.warehouseId === selectedWarehouse.id);
                    const updatedPermissions = permExists
                      ? currentPermissions
                      : [...currentPermissions, {
                          warehouseId: selectedWarehouse.id,
                          warehouseName: selectedWarehouse.name,
                          canIn: false, canOut: false, canTransfer: false, canAdjust: false
                        }];

                    return {
                      ...prev,
                      WarehouseName: selectedWarehouse.name,
                      WarehouseId: `${selectedWarehouse.id}`,
                      allowedWareHouseName: updatedAllowed,
                      warehousePermissions: updatedPermissions,
                    };
                  });
                }
              }
            }}
          >
            <option value="" disabled className="bg-white">
              اختر اسم المخزن
            </option>
            {wareHouseNames.map((method) => (
              <option key={method.id} value={method.id} className="bg-white">
                {method.name}
              </option>
            ))}
          </select>
          
          {WarehouseName && (
            <button
              className="absolute left-3 top-1/4"
              onClick={() => {
                setWarehouseName("");
                //setDefaultWarehouseName("")
                if (isUserMode) {
                  setScreenSettings(prev => ({ ...prev, WarehouseName: "", WarehouseId: "" }));
                }
              }}
            >
              <IoCloseSharp />
            </button>
          )}
        </div>
      </div>
      
      {isUserMode && (
        <>
        <hr className="w-[95%] mx-auto" />
      
      <h1 className="p-5 pr-2 font-semibold text-xl">صلاحيات المخازن:</h1>
      <div className="flex justify-between items-center p-3 text-lg">
        <h2>المخازن المسموحة :</h2>
        <div className="flex sm:w-[60%] justify-around items-center text-base">
          {wareHouseNames.map((method) => (
            <div className="flex items-center me-4" key={method.id}>
              <input
                id={`allowed-${method.id}`}
                type="checkbox"
                className="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                name="allowedWareHouseName"
                value={method.name}
                checked={allowedWareHouseName?.some((item) => item.id === method.id)}
                onChange={(e) => {
                  if (e.target.checked) {
                    setViewablePermissions((prev) =>
                      prev?.includes(method.id) ? prev : [...(prev || []), method.id]
                    );

                    if (isUserMode) {
                      setScreenSettings((prev) => {
                        const currentAllowed = prev?.allowedWareHouseName || [];
                        const currentPermissions = prev?.warehousePermissions || [];
                        const currentViewable = prev?.viewablePermissions || [];

                        return {
                          ...prev,
                          allowedWareHouseName: currentAllowed.some(i => i.id === method.id)
                            ? currentAllowed
                            : [...currentAllowed, { id: method.id, name: method.name, is_default: false }],
                          warehousePermissions: currentPermissions.some(p => p.warehouseId === method.id)
                            ? currentPermissions
                            : [...currentPermissions, {
                                warehouseId: method.id,
                                warehouseName: method.name,
                                canIn: false, canOut: false, canTransfer: false, canAdjust: false
                              }],
                          viewablePermissions: currentViewable.includes(method.id)
                            ? currentViewable
                            : [...currentViewable, method.id]
                        };
                      });
                    }
                  } else {
                    setViewablePermissions((prev) => (prev || []).filter((id) => id !== method.id));

                    if (isUserMode) {
                      setScreenSettings((prev) => ({
                        ...prev,
                        allowedWareHouseName: (prev?.allowedWareHouseName || []).filter((item) => item.id !== method.id),
                        warehousePermissions: (prev?.warehousePermissions || []).filter((p) => p.warehouseId !== method.id),
                        viewablePermissions: (prev?.viewablePermissions || []).filter((v) => v !== method.id),
                      }));
                    }
                  }
                }}
              />
              <label
                htmlFor={`allowed-${method.id}`}
                className="select-none ms-2 text-sm font-medium text-heading cursor-pointer"
              >
                {method.name}
              </label>
            </div>
          ))}
        </div>
      </div>
        </>
      )}
    </div>
  );
}