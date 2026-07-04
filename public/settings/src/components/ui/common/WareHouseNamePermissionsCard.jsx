import React, { useEffect } from "react";
import { IoIosArrowDown } from "react-icons/io";
import { FaExchangeAlt } from "react-icons/fa";
import { useScreensPermissions } from "../../../contexts/ScreensPermissionsContext";

export default function WareHouseNamePermissionsCard({
  allowedWareHouseName,
  viewablePermissions,
  setViewablePermissions,
}) {
  const operations = [
    { key: "canIn", label: "إضافة" },
    { key: "canOut", label: "صرف" },
    { key: "canTransfer", label: "نقل" },
    { key: "canAdjust", label: "جرد" },
  ];

  const { screenSettings, setScreenSettings } = useScreensPermissions();
  const warehousePermissions = screenSettings?.warehousePermissions || [];
  useEffect(() => {
    // const firstWarehouseId = allowedWareHouseName[0]?.id;
    const firstWarehouseId = allowedWareHouseName.map((ele) => ele.id);
    if (!firstWarehouseId) return;

    setViewablePermissions((prev) => {
      const currentPermissions = Array.isArray(prev) ? prev : [];

      return [...new Set([...currentPermissions, ...firstWarehouseId])];
    });
  }, [allowedWareHouseName]);

  const handleViewPermissions = (warehouseId) => {
    setViewablePermissions((prev) =>
      prev.includes(warehouseId)
        ? prev.filter((id) => id !== warehouseId)
        : [...prev, warehouseId],
    );
  };

  return (
    <div className="w-full mx-auto bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
      <div className="p-5 border-b border-slate-100 bg-slate-50/50">
        <h1 className="font-bold text-lg text-slate-800">
          صلاحيات حركات المخازن
        </h1>
      </div>

      <div className="p-5 space-y-4">
        {allowedWareHouseName.map((ele) => {
          const warehouse = warehousePermissions?.find(
            (item) => item?.warehouseId === ele?.id,
          );

          if (!warehouse) return null;
          const isOpen = viewablePermissions?.includes(warehouse?.warehouseId);

          const transferDestinations = allowedWareHouseName.filter(
            (item) => item.id !== warehouse.warehouseId,
          );

          return (
            <div
              key={warehouse.warehouseId}
              className={`border rounded-xl transition-all duration-200 overflow-hidden ${
                isOpen
                  ? "border-blue-500 shadow-xs ring-1 ring-blue-500/10"
                  : "border-slate-200 hover:border-slate-300"
              }`}
            >
              <button
                type="button"
                className={`w-full flex justify-between items-center p-4 text-right transition-colors duration-150 ${
                  isOpen ? "bg-blue-50/30" : "bg-white hover:bg-slate-50"
                }`}
                onClick={() => handleViewPermissions(warehouse.warehouseId)}
              >
                <h2
                  className={`font-bold text-sm ${isOpen ? "text-blue-600" : "text-slate-700"}`}
                >
                  {warehouse.warehouseName}
                </h2>
                <IoIosArrowDown
                  className={`text-base text-slate-400 transition-transform duration-300 ${
                    isOpen ? "rotate-180 text-blue-500" : ""
                  }`}
                />
              </button>

              {isOpen && (
                <div className="p-4 bg-white border-t border-slate-100 flex flex-col gap-4">
                  <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    {operations.map((operation) => (
                      <label
                        key={operation.key}
                        className={`flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all ${
                          warehouse[operation.key]
                            ? "bg-blue-50/60 border-blue-200 text-blue-700 font-bold"
                            : "bg-slate-50/40 border-slate-200/70 text-slate-600 hover:bg-slate-50"
                        }`}
                      >
                        <input
                          type="checkbox"
                          className="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/30 accent-blue-600 cursor-pointer"
                          checked={warehouse[operation.key] || false}
                          onChange={(e) => {
                            const isChecked = e.target.checked;

                            setScreenSettings((prev) => {
                              const updatedPermissions =
                                prev.warehousePermissions.map((item) => {
                                  if (
                                    item.warehouseId === warehouse.warehouseId
                                  ) {
                                    const updatedItem = {
                                      ...item,
                                      [operation.key]: isChecked,
                                    };
                                    if (
                                      operation.key === "canTransfer" &&
                                      !isChecked
                                    ) {
                                      delete updatedItem.transferToWarehouseId;
                                    }
                                    return updatedItem;
                                  }
                                  return item;
                                });

                              return {
                                ...prev,
                                warehousePermissions: updatedPermissions,
                              };
                            });
                          }}
                        />
                        <span className="text-xs">{operation.label}</span>
                      </label>
                    ))}
                  </div>

                  {warehouse.canTransfer && (
                    <div className="bg-amber-50/40 border border-amber-200/70 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 animate-fadeIn">
                      <div className="flex items-center gap-2 text-amber-800 text-xs font-bold">
                        <FaExchangeAlt className="text-amber-600" />
                        <span>
                          تحديد المخزن المتاح للنقل إليه من (
                          {warehouse.warehouseName}):
                        </span>
                      </div>

                      <div className="relative w-full sm:w-[45%]">
                        <select
                          className="w-full bg-white border border-amber-200 text-slate-800 text-xs font-semibold px-3 py-2 rounded-xl focus:outline-none focus:border-amber-500 appearance-none cursor-pointer"
                          value={warehouse.transferToWarehouseId || ""}
                          onChange={(e) => {
                            const targetId = e.target.value;
                            setScreenSettings((prev) => ({
                              ...prev,
                              warehousePermissions:
                                prev.warehousePermissions.map((item) =>
                                  item.warehouseId === warehouse.warehouseId
                                    ? {
                                        ...item,
                                        transferToWarehouseId: targetId,
                                      }
                                    : item,
                                ),
                            }));
                          }}
                        >
                          <option value="">كل المخازن المسموحة</option>
                          {transferDestinations.map((dest) => (
                            <option key={dest.id} value={dest.id}>
                              {dest.name}
                            </option>
                          ))}
                        </select>
                        <div className="absolute left-3 top-3 text-slate-400 pointer-events-none text-xs">
                          <IoIosArrowDown />
                        </div>
                      </div>
                    </div>
                  )}
                </div>
              )}
            </div>
          );
        })}
      </div>
    </div>
  );
}
