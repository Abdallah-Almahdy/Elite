import { createContext, useContext, useState } from "react";

const ScreensPermissionsContext = createContext(null);

export const ScreensPermissionsProvider = ({ children }) => {
  const savedData = JSON.parse(localStorage?.getItem("Screens Settings")) || {
    userName: "",
    posShow: false,
    posPriceChangeAuth: false,
    posChangeDiscount: false,
    posDeleteProdWithPass: false,
    posInvoiceTypeChangeAuth: false,
    posPaymentMethodChangeAuth: false,
    posSaveNoPrintAuth: false,
    posEditDate: false,
    posChooseClient: false,
    posInvoiceFreeze: false,
    posInvoiceCall: false,
    posPriceChange: false,
    posChangeTax: false,
    posInvoiceCancel: false,
    posShiftClose: false,
    adShow: false,
    notificationShow: false,
    bromocodeShow: false,
    warehouseShow: false,
    warehouseEdit: false,
    warehouseDelete: false,
    createUser: false,
    configUpdate: false,
    productCreate: false,
    productEdit: false,
    productDelete: false,
    productShowSidebar: false,
    productShowGeneral: false,
    sectionCreate: false,
    sectionEdit: false,
    sectionDelete: false,
    sectionShowSidebar: false,
    orderShow: false,
    orderPrepare: false,
    orderCancel: false,
    orderFinish: false,
    orderShipment: false,
    orderShowSidebar: false,
    reportShow: false,
    deliveryShow: false,
    deliveryEdit: false,
    deliveryDelete: false,
    deliveryAddArea: false,
    deliveryFreeDelivery: false,
    aboutUsShow: false,
    evaluationShow: false,
    customerShow: false,
    customerShowMessages: false,
    kitchenShow: false,
    statisticsShow: false,
    supplierShow: false,
    unitShow: false,
  };
  const [screenSettings, setScreenSettings] = useState(
    savedData || {
      userName: "",
      posShow: false,
      posPriceChangeAuth: false,
      posChangeDiscount: false,
      posDeleteProdWithPass: false,
      posInvoiceTypeChangeAuth: false,
      posPaymentMethodChangeAuth: false,
      posSaveNoPrintAuth: false,
      posEditDate: false,
      posChooseClient: false,
      posInvoiceFreeze: false,
      posInvoiceCall: false,
      posPriceChange: false,
      posChangeTax: false,
      posInvoiceCancel: false,
      posShiftClose: false,
      adShow: false,
      notificationShow: false,
      bromocodeShow: false,
      warehouseShow: false,
      warehouseEdit: false,
      warehouseDelete: false,
      createUser: false,
      configUpdate: false,
      productCreate: false,
      productEdit: false,
      productDelete: false,
      productShowSidebar: false,
      productShowGeneral: false,
      sectionCreate: false,
      sectionEdit: false,
      sectionDelete: false,
      sectionShowSidebar: false,
      orderShow: false,
      orderPrepare: false,
      orderCancel: false,
      orderFinish: false,
      orderShipment: false,
      orderShowSidebar: false,
      reportShow: false,
      deliveryShow: false,
      deliveryEdit: false,
      deliveryDelete: false,
      deliveryAddArea: false,
      deliveryFreeDelivery: false,

      aboutUsShow: false,
      evaluationShow: false,
      customerShow: false,
      customerShowMessages: false,
      kitchenShow: false,
      statisticsShow: false,
      supplierShow: false,
      unitShow: false,
    },
  );

  return (
    <ScreensPermissionsContext.Provider
      value={{
        screenSettings,
        setScreenSettings,
      }}
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
