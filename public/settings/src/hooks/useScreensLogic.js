import React, { useEffect } from "react";
import { useSelectedScreens } from "../contexts/SelectedScreensContext";
import { useInvoiceSettings } from "../contexts/InvoiceSettingsContext";
import { useDispatch, useSelector } from "react-redux";
import { fetchPermissions } from "../store/reducers/settingSlice";

export default function useScreensLogic() {
  const { selectedScreens, setSelectedScreens } = useSelectedScreens();
  const { updateScreenSettings } = useInvoiceSettings();
  const storedPermissions = useSelector((state) => state?.setting?.permissions);

  const permissionsNamingMapping = {
    configUpdate: "config.update",
    deliveryAddArea: "delevary.addArea",
    deliveryFreeDelivery: "delevary.freeDelevary",
    deliveryDelete: "delivery.delete",
    deliveryEdit: "delivery.edit",
    orderCancel: "order.cancel",
    orderFinish: "order.finish",
    orderPrepare: "order.prepare",
    orderShipment: "order.shipment",
    orderShow: "order.show",
    posChangeDiscount: "pos.changeDiscount",
    posChangeTax: "pos.changeTax",
    posChooseClient: "pos.chooseClient",
    posDeleteProdWithPass: "pos.deleteProdWithPass",
    posEditDate: "pos.editDate",
    posInvoiceCall: "pos.InviceCall",
    posInvoiceCancel: "pos.InviceCancel",
    posInvoiceFreeze: "pos.InviceFreeze",
    posInvoiceTypeChangeAuth: "pos.InvoiceTypeChangeAuth",
    posPaymentMethodChangeAuth: "pos.paymentMethodChangeAuth",
    posPriceChange: "pos.priceChange",
    posPriceChangeAuth: "pos.priceChangeAuth",
    posSaveNoPrintAuth: "pos.saveNoPrintAuth",
    posShiftClose: "pos.shiiftClose",
    posShow: "pos.show",
    productCreate: "product.create",
    productDelete: "product.delete",
    productEdit: "product.edit",
    reportShow: "reports.show",
    sectionCreate: "section.create",
    sectionDelete: "section.delete",
    sectionEdit: "section.edit",
    adShow: "showAds",
    deliveryShow: "showDelevary",
    productShowGeneral: "showGenralProducts",
    notificationShow: "showNotifications",
    orderShowSidebar: "showOrdersSidebar",
    productShowSidebar: "showProductsSidebar",
    bromocodeShow: "showPromoCodes",
    sectionShowSidebar: "showSectionsSidebar",
    createUser: "user.create",
    warehouseDelete: "warehouse.delete",
    warehouseEdit: "warehouse.edit",
    warehouseShow: "warehouse.show",
    aboutUsShow: "showAboutUs",
    evaluationShow: "showClientsVote",
    customerShow: "showCustomers",
    customerShowMessages: "showCustomersMessages",
    kitchenShow: "showKitche",
    statisticsShow: "showStatistics",
    supplierShow: "showSuppliers",
    unitShow: "showUnits",
  };

  const screenPermissionsMap = {
    "شاشة البيع": [
      "posShow",
      "posPriceChangeAuth",
      "posChangeDiscount",
      "posDeleteProdWithPass",
      "posInvoiceTypeChangeAuth",
      "posPaymentMethodChangeAuth",
      "posSaveNoPrintAuth",
      "posEditDate",
      "posChooseClient",
      "posInvoiceFreeze",
      "posInvoiceCall",
      "posPriceChange",
      "posChangeTax",
      "posInvoiceCancel",
      "posShiftClose",
    ],

    "شاشة الدليفرى": [
      "deliveryShow",
      "deliveryEdit",
      "deliveryDelete",
      "deliveryAddArea",
      "deliveryFreeDelivery",
    ],

    "شاشة الاعلانات": ["adShow"],
    "شاشة الاشعارات": ["notificationShow"],
    "شاشة البروموكود": ["bromocodeShow"],

    "شاشة المخازن": ["warehouseShow", "warehouseEdit", "warehouseDelete"],

    "شاشة انشاء مستخدم": ["createUser"],
    "شاشة الاعدادات": ["configUpdate"],

    "شاشة المنتجات": [
      "productCreate",
      "productEdit",
      "productDelete",
      "productShowSidebar",
      "productShowGeneral",
    ],

    "شاشة الاقسام": [
      "sectionCreate",
      "sectionEdit",
      "sectionDelete",
      "sectionShowSidebar",
    ],

    "شاشة الطلبيات": [
      "orderShow",
      "orderPrepare",
      "orderCancel",
      "orderFinish",
      "orderShipment",
      "orderShowSidebar",
    ],

    "شاشة التقارير": ["reportShow"],
    "شاشة المطابخ": ["kitchenShow"],
    "شاشة الاحصائيات": ["statisticsShow"],
    "شاشة الموردين": ["supplierShow"],
    "شاشة الوحدات": ["unitShow"],
    "شاشة العملاء": ["customerShow"],
    "شاشة شكاوى العملاء": ["customerShowMessages"],
    "شاشة التقييمات ": ["evaluationShow"],
    عننا: ["aboutUsShow"],
  };

  const handleSelectScreen = (screen) => {
    const isSelected = !selectedScreens[screen.name];

    const permissions = screenPermissionsMap[screen.name];

    if (!permissions) return;

    const payload = {};

    permissions.forEach((permission) => {
      payload[permission] = !isSelected
        ? isSelected
        : (storedPermissions?.[permissionsNamingMapping[permission]] ??
          isSelected);
    });

    if (screen.name === "شاشة المخازن" && !isSelected) {
      payload["WarehouseName"] = "";
    }

    updateScreenSettings((prev) => ({
      ...prev,
      ...payload,
    }));

    setSelectedScreens((prev) => ({
      ...prev,
      [screen.name]: isSelected,
    }));
  };
  return {
    handleSelectScreen,
  };
}
