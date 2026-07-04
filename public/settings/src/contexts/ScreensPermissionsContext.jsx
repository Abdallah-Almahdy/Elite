import { createContext, useContext, useEffect, useMemo, useState } from "react";
import { useSelector, useDispatch } from "react-redux";
import {
  fetchConfigs,
  fetchPermissions,
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
  const saved = JSON.parse(localStorage.getItem("Screens Settings"));

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
  }, [userPermissions, mainWarehouse, staticWarehouses]);

  const [screenSettings, setScreenSettings] = useState(() => {
    return saved || null;
  });

  useEffect(() => {
    if (!userPermissions || !screenSettings) return;

    setScreenSettings((prev) => ({
      ...prev,

      posShow: saved?.posShow ?? userPermissions?.["pos.show"] ?? true,
      posPriceChangeAuth:
        saved?.posPriceChangeAuth ??
        userPermissions?.["pos.priceChangeAuth"] ??
        true,
      posChangeDiscount:
        saved?.posChangeDiscount ??
        userPermissions?.["pos.changeDiscount"] ??
        true,
      posDeleteProdWithPass:
        saved?.posDeleteProdWithPass ??
        userPermissions?.["pos.deleteProdWithPass"] ??
        true,
      posInvoiceTypeChangeAuth:
        saved?.posInvoiceTypeChangeAuth ??
        userPermissions?.["pos.InvoiceTypeChangeAuth"] ??
        true,
      posPaymentMethodChangeAuth:
        saved?.posPaymentMethodChangeAuth ??
        userPermissions?.["pos.paymentMethodChangeAuth"] ??
        true,
      posSaveNoPrintAuth:
        saved?.posSaveNoPrintAuth ??
        userPermissions?.["pos.saveNoPrintAuth"] ??
        true,
      posEditDate:
        saved?.posEditDate ?? userPermissions?.["pos.editDate"] ?? true,
      posChooseClient:
        saved?.posChooseClient ?? userPermissions?.["pos.chooseClient"] ?? true,
      posInvoiceFreeze:
        saved?.posInvoiceFreeze ??
        userPermissions?.["pos.InviceFreeze"] ??
        true,
      posInvoiceCall:
        saved?.posInvoiceCall ?? userPermissions?.["pos.InviceCall"] ?? true,
      posPriceChange:
        saved?.posPriceChange ?? userPermissions?.["pos.priceChange"] ?? true,
      posChangeTax:
        saved?.posChangeTax ?? userPermissions?.["pos.changeTax"] ?? true,
      posInvoiceCancel:
        saved?.posInvoiceCancel ??
        userPermissions?.["pos.InviceCancel"] ??
        true,
      posShiftClose:
        saved?.posShiftClose ?? userPermissions?.["pos.shiiftClose"] ?? true,

      adShow: saved?.adShow ?? userPermissions?.["showAds"] ?? true,
      notificationShow:
        saved?.notificationShow ??
        userPermissions?.["showNotifications"] ??
        true,
      bromocodeShow:
        saved?.bromocodeShow ?? userPermissions?.["showPromoCodes"] ?? true,

      warehouseShow:
        saved?.warehouseShow ?? userPermissions?.["warehouse.show"] ?? true,
      warehouseEdit:
        saved?.warehouseEdit ?? userPermissions?.["warehouse.edit"] ?? true,
      warehouseDelete:
        saved?.warehouseDelete ?? userPermissions?.["warehouse.delete"] ?? true,

      createUser: saved?.createUser ?? userPermissions?.["user.create"] ?? true,
      configUpdate:
        saved?.configUpdate ?? userPermissions?.["config.update"] ?? true,

      productCreate:
        saved?.productCreate ?? userPermissions?.["product.create"] ?? true,
      productEdit:
        saved?.productEdit ?? userPermissions?.["product.edit"] ?? true,
      productDelete:
        saved?.productDelete ?? userPermissions?.["product.delete"] ?? true,
      productShowSidebar:
        saved?.productShowSidebar ??
        userPermissions?.["showProductsSidebar"] ??
        true,
      productShowGeneral:
        saved?.productShowGeneral ??
        userPermissions?.["showGenralProducts"] ??
        true,

      sectionCreate:
        saved?.sectionCreate ?? userPermissions?.["section.create"] ?? true,
      sectionEdit:
        saved?.sectionEdit ?? userPermissions?.["section.edit"] ?? true,
      sectionDelete:
        saved?.sectionDelete ?? userPermissions?.["section.delete"] ?? true,
      sectionShowSidebar:
        saved?.sectionShowSidebar ??
        userPermissions?.["showSectionsSidebar"] ??
        true,

      orderShow: saved?.orderShow ?? userPermissions?.["order.show"] ?? true,
      orderPrepare:
        saved?.orderPrepare ?? userPermissions?.["order.prepare"] ?? true,
      orderCancel:
        saved?.orderCancel ?? userPermissions?.["order.cancel"] ?? true,
      orderFinish:
        saved?.orderFinish ?? userPermissions?.["order.finish"] ?? true,
      orderShipment:
        saved?.orderShipment ?? userPermissions?.["order.shipment"] ?? true,
      orderShowSidebar:
        saved?.orderShowSidebar ??
        userPermissions?.["showOrdersSidebar"] ??
        true,

      reportShow:
        saved?.reportShow ?? userPermissions?.["reports.show"] ?? true,

      deliveryShow:
        saved?.deliveryShow ?? userPermissions?.["showDelevary"] ?? true,
      deliveryEdit:
        saved?.deliveryEdit ?? userPermissions?.["delivery.edit"] ?? true,
      deliveryDelete:
        saved?.deliveryDelete ?? userPermissions?.["delivery.delete"] ?? true,
      deliveryAddArea:
        saved?.deliveryAddArea ?? userPermissions?.["delevary.addArea"] ?? true,
      deliveryFreeDelivery:
        saved?.deliveryFreeDelivery ??
        userPermissions?.["delevary.freeDelevary"] ??
        true,

      aboutUsShow:
        saved?.aboutUsShow ?? userPermissions?.["showAboutUs"] ?? true,
      evaluationShow:
        saved?.evaluationShow ?? userPermissions?.["showClientsVote"] ?? true,
      customerShow:
        saved?.customerShow ?? userPermissions?.["showCustomers"] ?? true,
      customerShowMessages:
        saved?.customerShowMessages ??
        userPermissions?.["showCustomersMessages"] ??
        true,
      kitchenShow:
        saved?.kitchenShow ?? userPermissions?.["showKitchen"] ?? true,
      statisticsShow:
        saved?.statisticsShow ?? userPermissions?.["showStatistics"] ?? true,
      supplierShow:
        saved?.supplierShow ?? userPermissions?.["showSuppliers"] ?? true,
      unitShow: saved?.unitShow ?? userPermissions?.["showUnits"] ?? true,
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
      localStorage.setItem("Screens Settings", JSON.stringify(screenSettings));
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
