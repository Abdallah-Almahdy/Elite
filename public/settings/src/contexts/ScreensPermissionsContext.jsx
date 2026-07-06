import { createContext, useContext, useEffect, useMemo, useState } from "react";
import { useSelector, useDispatch } from "react-redux";
import {
  fetchConfigs,
  fetchUserPermissions,
  fetchWarehouseNames,
} from "../store/reducers/settingSlice";
import { fetchClientsNames } from "../store/reducers/userSlice";

const ScreensPermissionsContext = createContext(null);

export const ScreensPermissionsProvider = ({ children }) => {
  const dispatch = useDispatch();
  const userPermissions = useSelector(
    (state) => state?.setting?.userPermissions,
  );
  const mainWarehouse = useSelector((state) => state?.setting?.mainWarehouse);

  const staticWarehouses = useSelector(
    (state) => state?.setting?.warehouseNames || [],
  );
  const saved = JSON.parse(sessionStorage.getItem("Screens Settings"));
    const [screenSettings, setScreenSettings] = useState(() => {
    return saved || null;
  });

  useEffect(() => {
    dispatch(fetchConfigs());
    dispatch(fetchClientsNames());
    dispatch(fetchWarehouseNames());
  }, [dispatch]);

  const baseSettings = useMemo(() => {
    if (saved) {
      return saved;
    }

    if (!staticWarehouses.length) {
      return null;
    }

    if (saved && saved.WarehouseName) {
      return saved;
    }
    const defaultWarehouse = staticWarehouses?.find((w)=> w?.is_default === true)
    const fallbackName = defaultWarehouse?.name || "مخزن 1";
    const matchedWarehouse =
      defaultWarehouse ??
      staticWarehouses[0];
    const defaultAllowedList = [
      { id: defaultWarehouse.id, name: defaultWarehouse.name, div: "vis" },
    ];

    const defaultWarehousePermissions = defaultAllowedList.map((w) => ({
      warehouseId: w.id,
      warehouseName: w.name,
      canIn: false,
      canOut: false,
      canTransfer: false,
      canAdjust: false,
    }));

    return {
      errors: { password: "" },
      isPasswordSent: false,
      userName: saved?.userName || "",
      userId: saved?.userId || "",
      WarehouseName: fallbackName,
      warehouseId: `${staticWarehouses?.find((w) => w?.name === fallbackName)?.id}`,

      posShow: userPermissions?.["pos.show"] ?? true,
      posPriceChangeAuth: userPermissions?.["pos.priceChangeAuth"] ?? true,
      posChangeDiscount: userPermissions?.["pos.changeDiscount"] ?? true,
      posDeleteProdWithPass:
        userPermissions?.["pos.deleteProdWithPass"] ?? true,
      posInvoiceTypeChangeAuth:
        userPermissions?.["pos.InvoiceTypeChangeAuth"] ?? true,
      posPaymentMethodChangeAuth:
        userPermissions?.["pos.paymentMethodChangeAuth"] ?? true,
      posSaveNoPrintAuth: userPermissions?.["pos.saveNoPrintAuth"] ?? true,
      posEditDate: userPermissions?.["pos.editDate"] ?? true,
      posChooseClient: userPermissions?.["pos.chooseClient"] ?? true,
      posInvoiceFreeze: userPermissions?.["pos.InviceFreeze"] ?? true,
      posInvoiceCall: userPermissions?.["pos.InviceCall"] ?? true,
      posPriceChange: userPermissions?.["pos.priceChange"] ?? true,
      posChangeTax: userPermissions?.["pos.changeTax"] ?? true,
      posInvoiceCancel: userPermissions?.["pos.InviceCancel"] ?? true,
      posShiftClose: userPermissions?.["pos.shiiftClose"] ?? true,

      adShow: userPermissions?.["showAds"] ?? true,
      notificationShow: userPermissions?.["showNotifications"] ?? true,
      bromocodeShow: userPermissions?.["showPromoCodes"] ?? true,

      warehouseShow: userPermissions?.["warehouse.show"] ?? true,
      warehouseEdit: userPermissions?.["warehouse.edit"] ?? true,
      warehouseDelete: userPermissions?.["warehouse.delete"] ?? true,

      createUser: userPermissions?.["user.create"] ?? true,
      configUpdate: userPermissions?.["config.update"] ?? true,

      productCreate: userPermissions?.["product.create"] ?? true,
      productEdit: userPermissions?.["product.edit"] ?? true,
      productDelete: userPermissions?.["product.delete"] ?? true,
      productShowSidebar: userPermissions?.["showProductsSidebar"] ?? true,
      productShowGeneral: userPermissions?.["showGenralProducts"] ?? true,

      sectionCreate: userPermissions?.["section.create"] ?? true,
      sectionEdit: userPermissions?.["section.edit"] ?? true,
      sectionDelete: userPermissions?.["section.delete"] ?? true,
      sectionShowSidebar: userPermissions?.["showSectionsSidebar"] ?? true,

      orderShow: userPermissions?.["order.show"] ?? true,
      orderPrepare: userPermissions?.["order.prepare"] ?? true,
      orderCancel: userPermissions?.["order.cancel"] ?? true,
      orderFinish: userPermissions?.["order.finish"] ?? true,
      orderShipment: userPermissions?.["order.shipment"] ?? true,
      orderShowSidebar: userPermissions?.["showOrdersSidebar"] ?? true,

      reportShow: userPermissions?.["reports.show"] ?? true,

      deliveryShow: userPermissions?.["showDelevary"] ?? true,
      deliveryEdit: userPermissions?.["delivery.edit"] ?? true,
      deliveryDelete: userPermissions?.["delivery.delete"] ?? true,
      deliveryAddArea: userPermissions?.["delevary.addArea"] ?? true,
      deliveryFreeDelivery: userPermissions?.["delevary.freeDelevary"] ?? true,

      aboutUsShow: userPermissions?.["showAboutUs"] ?? true,
      evaluationShow: userPermissions?.["showClientsVote"] ?? true,
      customerShow: userPermissions?.["showCustomers"] ?? true,
      customerShowMessages: userPermissions?.["showCustomersMessages"] ?? true,
      kitchenShow: userPermissions?.["showKitchen"] ?? true,
      statisticsShow: userPermissions?.["showStatistics"] ?? true,
      supplierShow: userPermissions?.["showSuppliers"] ?? true,
      unitShow: userPermissions?.["showUnits"] ?? true,

      allowedWareHouseName: defaultAllowedList,
      warehousePermissions: defaultWarehousePermissions,
      viewablePermissions: [defaultAllowedList[0].id],
    };
  }, [userPermissions, mainWarehouse, staticWarehouses, screenSettings?.userId]);


  useEffect(() => {
    if (!userPermissions || !screenSettings) return;

    setScreenSettings((prev) => ({
      ...prev,

       posShow: userPermissions?.["pos.show"] ?? true,
      posPriceChangeAuth: userPermissions?.["pos.priceChangeAuth"] ?? true,
      posChangeDiscount: userPermissions?.["pos.changeDiscount"] ?? true,
      posDeleteProdWithPass:
        userPermissions?.["pos.deleteProdWithPass"] ?? true,
      posInvoiceTypeChangeAuth:
        userPermissions?.["pos.InvoiceTypeChangeAuth"] ?? true,
      posPaymentMethodChangeAuth:
        userPermissions?.["pos.paymentMethodChangeAuth"] ?? true,
      posSaveNoPrintAuth: userPermissions?.["pos.saveNoPrintAuth"] ?? true,
      posEditDate: userPermissions?.["pos.editDate"] ?? true,
      posChooseClient: userPermissions?.["pos.chooseClient"] ?? true,
      posInvoiceFreeze: userPermissions?.["pos.InviceFreeze"] ?? true,
      posInvoiceCall: userPermissions?.["pos.InviceCall"] ?? true,
      posPriceChange: userPermissions?.["pos.priceChange"] ?? true,
      posChangeTax: userPermissions?.["pos.changeTax"] ?? true,
      posInvoiceCancel: userPermissions?.["pos.InviceCancel"] ?? true,
      posShiftClose: userPermissions?.["pos.shiiftClose"] ?? true,

      adShow: userPermissions?.["showAds"] ?? true,
      notificationShow: userPermissions?.["showNotifications"] ?? true,
      bromocodeShow: userPermissions?.["showPromoCodes"] ?? true,

      warehouseShow: userPermissions?.["warehouse.show"] ?? true,
      warehouseEdit: userPermissions?.["warehouse.edit"] ?? true,
      warehouseDelete: userPermissions?.["warehouse.delete"] ?? true,

      createUser: userPermissions?.["user.create"] ?? true,
      configUpdate: userPermissions?.["config.update"] ?? true,

      productCreate: userPermissions?.["product.create"] ?? true,
      productEdit: userPermissions?.["product.edit"] ?? true,
      productDelete: userPermissions?.["product.delete"] ?? true,
      productShowSidebar: userPermissions?.["showProductsSidebar"] ?? true,
      productShowGeneral: userPermissions?.["showGenralProducts"] ?? true,

      sectionCreate: userPermissions?.["section.create"] ?? true,
      sectionEdit: userPermissions?.["section.edit"] ?? true,
      sectionDelete: userPermissions?.["section.delete"] ?? true,
      sectionShowSidebar: userPermissions?.["showSectionsSidebar"] ?? true,

      orderShow: userPermissions?.["order.show"] ?? true,
      orderPrepare: userPermissions?.["order.prepare"] ?? true,
      orderCancel: userPermissions?.["order.cancel"] ?? true,
      orderFinish: userPermissions?.["order.finish"] ?? true,
      orderShipment: userPermissions?.["order.shipment"] ?? true,
      orderShowSidebar: userPermissions?.["showOrdersSidebar"] ?? true,

      reportShow: userPermissions?.["reports.show"] ?? true,

      deliveryShow: userPermissions?.["showDelevary"] ?? true,
      deliveryEdit: userPermissions?.["delivery.edit"] ?? true,
      deliveryDelete: userPermissions?.["delivery.delete"] ?? true,
      deliveryAddArea: userPermissions?.["delevary.addArea"] ?? true,
      deliveryFreeDelivery: userPermissions?.["delevary.freeDelevary"] ?? true,

      aboutUsShow: userPermissions?.["showAboutUs"] ?? true,
      evaluationShow: userPermissions?.["showClientsVote"] ?? true,
      customerShow: userPermissions?.["showCustomers"] ?? true,
      customerShowMessages: userPermissions?.["showCustomersMessages"] ?? true,
      kitchenShow: userPermissions?.["showKitchen"] ?? true,
      statisticsShow: userPermissions?.["showStatistics"] ?? true,
      supplierShow: userPermissions?.["showSuppliers"] ?? true,
      unitShow: userPermissions?.["showUnits"] ?? true,
    }));
  }, [userPermissions]);

  useEffect(() => {
    if (!baseSettings) return;

    setScreenSettings((prev) => {
      if (prev) return prev;

      return baseSettings;
    });
  }, [baseSettings]);

  useEffect(() => {
    const currentUserId = screenSettings?.userId;
    if (currentUserId) {
      dispatch(fetchUserPermissions({ id: currentUserId }));
    }
  }, [dispatch, screenSettings?.userId]);

  useEffect(() => {
    if (!mainWarehouse) return;

    if (saved && saved.WarehouseName) return;

    const matchedWarehouse = staticWarehouses.find(
      (w) => w.name === mainWarehouse,
    );
    if (!matchedWarehouse) return;

    setScreenSettings((prev) => {
      const currentAllowed = prev?.allowedWareHouseName || [];
      const currentPermissions = prev?.warehousePermissions || [];
      const currentViewable = prev?.viewablePermissions || [];

      const isAlreadyAllowed = currentAllowed.some(
        (item) => item.id === matchedWarehouse.id,
      );
      const updatedAllowed = isAlreadyAllowed
        ? currentAllowed
        : [
            ...currentAllowed,
            {
              id: matchedWarehouse.id,
              name: matchedWarehouse.name,
              div: "vis",
            },
          ];

      const isAlreadyVisible = currentViewable.some(
        (v) => v === matchedWarehouse.id,
      );
      const updatedViewable = isAlreadyVisible
        ? currentViewable
        : [...currentViewable, matchedWarehouse?.id];

      const isPermissionExist = currentPermissions.some(
        (p) => p.warehouseId === matchedWarehouse.id,
      );
      const updatedPermissions = isPermissionExist
        ? currentPermissions
        : [
            ...currentPermissions,
            {
              warehouseId: matchedWarehouse.id,
              warehouseName: matchedWarehouse.name,
              canIn: false,
              canOut: false,
              canTransfer: false,
              canAdjust: false,
            },
          ];

      return {
        ...prev,
        WarehouseName: prev?.WarehouseName || mainWarehouse,
        warehouseId:
          prev?.warehouseId ||
          staticWarehouses?.filter((w) => w?.name === mainWarehouse)?.id,

        allowedWareHouseName:
          currentAllowed.length > 0 ? currentAllowed : updatedAllowed,
        warehousePermissions:
          currentPermissions.length > 0
            ? currentPermissions
            : updatedPermissions,
        viewablePermissions:
          currentViewable.length > 0 ? currentViewable : updatedViewable,
      };
    });
  }, [mainWarehouse]);

  useEffect(() => {
    if (screenSettings) {
      sessionStorage.setItem("Screens Settings", JSON.stringify(screenSettings));
    }
  }, [screenSettings]);

  return (
    <ScreensPermissionsContext.Provider
      value={{ screenSettings, setScreenSettings }}
    >
      {children}
    </ScreensPermissionsContext.Provider>
  );
};

export const useScreensPermissions = () => {
  const context = useContext(ScreensPermissionsContext);
  if (!context) {
    throw new Error(
      "useScreensPermissions must be used inside ScreensPermissionsProvider",
    );
  }
  return context;
};
