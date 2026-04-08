import { useState } from "react";
import { useSelector, useDispatch } from "react-redux";

export default function useScreenSettingsPreferenceLogic() {
  const savedScreenSetting = JSON.parse(
    localStorage.getItem("Screens Settings"),
  );
  const defaultWarehouseName = JSON.parse(
    localStorage.getItem("Invoice Settings"),
  );

  const users = useSelector((state) => state.user?.users);
  const [userName, setUserName] = useState(savedScreenSetting?.userName || "");
  const [userNameResult, setUserNameResult] = useState("");
  const [userNameShowResult, setUserNameShowResult] = useState(false);
  const [screenName, setScreenName] = useState("pos_screen");
  const [posShow, setPosShow] = useState(savedScreenSetting?.posShow || false);
  const [posPriceChangeAuth, setPosPriceChangeAuth] = useState(
    savedScreenSetting?.posPriceChangeAuth || false,
  );
  const [posChangeDiscount, setPosChangeDiscount] = useState(
    savedScreenSetting?.posChangeDiscount || false,
  );
  const [posDeleteProdWithPass, setPosDeleteProdWithPass] = useState(
    savedScreenSetting?.posDeleteProdWithPass || false,
  );
  const [posPassword, setPosPassword] = useState("");
  const [posInvoiceTypeChangeAuth, setPosInvoiceTypeChangeAuth] = useState(
    savedScreenSetting?.posInvoiceTypeChangeAuth || false,
  );
  const [posPaymentMethodChangeAuth, setPosPaymentMethodChangeAuth] = useState(
    savedScreenSetting?.posPaymentMethodChangeAuth || false,
  );
  const [posSaveNoPrintAuth, setPosSaveNoPrintAuth] = useState(
    savedScreenSetting?.posSaveNoPrintAuth || false,
  );
  const [posEditDate, setPosEditDate] = useState(
    savedScreenSetting?.posEditDate || false,
  );
  const [posChooseClient, setPosChooseClient] = useState(
    savedScreenSetting?.posChooseClient || false,
  );
  const [posInvoiceFreeze, setPosInvoiceFreeze] = useState(
    savedScreenSetting?.posInvoiceFreeze || false,
  );
  const [posInvoiceCall, setPosInvoiceCall] = useState(
    savedScreenSetting?.posInvoiceCall || false,
  );
  const [posPriceChange, setPosPriceChange] = useState(
    savedScreenSetting?.posPriceChange || false,
  );
  const [posChangeTax, setPosChangeTax] = useState(
    savedScreenSetting?.posChangeTax || false,
  );
  const [posInvoiceCancel, setPosInvoiceCancel] = useState(
    savedScreenSetting?.posInvoiceCancel || false,
  );
  const [posShiftClose, setPosShiftClose] = useState(
    savedScreenSetting?.posShiftClose || false,
  );
  const [adShow, setAdShow] = useState(savedScreenSetting?.adShow || false);
  const [notificationShow, setNotificationShow] = useState(
    savedScreenSetting?.notificationShow || false,
  );
  const [bromocodeShow, setBromocodeShow] = useState(
    savedScreenSetting?.bromocodeShow || false,
  );
  const [WarehouseName, setWarehouseName] = useState(
    defaultWarehouseName?.defaultWarehouseName || "مخزن 1",
  );
  const [warehouseShow, setWarehouseShow] = useState(
    savedScreenSetting?.warehouseShow || false,
  );
  const [warehouseEdit, setWarehouseEdit] = useState(
    savedScreenSetting?.warehouseEdit || false,
  );
  const [warehouseDelete, setWarehouseDelete] = useState(
    savedScreenSetting?.warehouseDelete || false,
  );
  const [createUser, setCreateUser] = useState(
    savedScreenSetting?.createUser || false,
  );
  const [configUpdate, setConfigUpdate] = useState(
    savedScreenSetting?.configUpdate || false,
  );
  const [productCreate, setProductCreate] = useState(
    savedScreenSetting?.productCreate || false,
  );
  const [productEdit, setProductEdit] = useState(
    savedScreenSetting?.productEdit || false,
  );
  const [productDelete, setProductDelete] = useState(
    savedScreenSetting?.productDelete || false,
  );
  const [productShowSidebar, setProductShowSidebar] = useState(
    savedScreenSetting?.productShowSidebar || false,
  );
  const [productShowGeneral, setProductShowGeneral] = useState(
    savedScreenSetting?.productShowGeneral || false,
  );
  const [sectionCreate, setSectionCreate] = useState(
    savedScreenSetting?.sectionCreate || false,
  );
  const [sectionEdit, setSectionEdit] = useState(
    savedScreenSetting?.sectionEdit || false,
  );
  const [sectionDelete, setSectionDelete] = useState(
    savedScreenSetting?.sectionDelete || false,
  );
  const [sectionShowSidebar, setSectionShowSidebar] = useState(
    savedScreenSetting?.sectionShowSidebar || false,
  );
  const [orderShow, setOrderShow] = useState(
    savedScreenSetting?.orderShow || false,
  );
  const [orderPrepare, setOrderPrepare] = useState(
    savedScreenSetting?.orderPrepare || false,
  );
  const [orderCancel, setOrderCancel] = useState(
    savedScreenSetting?.orderCancel || false,
  );
  const [orderShipment, setOrderShipment] = useState(
    savedScreenSetting?.orderShipment || false,
  );
  const [orderFinish, setOrderFinish] = useState(
    savedScreenSetting?.orderFinish || false,
  );
  const [orderShowSidebar, setOrderShowSidebar] = useState(
    savedScreenSetting?.orderShowSidebar || false,
  );
  const [reportShow, setReportShow] = useState(
    savedScreenSetting?.reportShow || false,
  );
  const [deliveryShow, setDeliveryShow] = useState(
    savedScreenSetting?.deliveryShow || false,
  );
  const [deliveryEdit, setDeliveryEdit] = useState(
    savedScreenSetting?.deliveryEdit || false,
  );
  const [deliveryDelete, setDeliveryDelete] = useState(
    savedScreenSetting?.deliveryDelete || false,
  );
  const [deliveryAddArea, setDeliveryAddArea] = useState(
    savedScreenSetting?.deliveryAddArea || false,
  );
  const [deliveryFreeDelivery, setDeliveryFreeDelivery] = useState(
    savedScreenSetting?.deliveryFreeDelivery || false,
  );
  const [kitchenShow, setKitchenShow] = useState(
    savedScreenSetting?.kitchenShow || false,
  );
  const [supplierShow, setSupplierShow] = useState(
    savedScreenSetting?.supplierShow || false,
  );
  const [unitShow, setUnitShow] = useState(
    savedScreenSetting?.unitShow || false,
  );
  const [customerShow, setCustomerShow] = useState(
    savedScreenSetting?.customerShow || false,
  );
  const [customerShowMessages, setCustomerShowMessages] = useState(
    savedScreenSetting?.customerShowMessages || false,
  );
  const [statisticsShow, setStatisticsShow] = useState(
    savedScreenSetting?.statisticsShow || false,
  );
  const [evaluationShow, setEvaluationShow] = useState(
    savedScreenSetting?.evaluationShow || false,
  );
  const [aboutUsShow, setAboutUsShow] = useState(
    savedScreenSetting?.aboutUsShow || false,
  );
  const [errors, setErrors] = useState({
    userName: "",
    printerName: "",
    password: "",
    confirmPassword: "",
  });

  {
    /* Function Returns The Result Of The Search By Name */
  }
  const handleSearchUserName = (val) => {
    if (!val) return [];
    let matches = [];
    users?.forEach((user) => {
      if (user?.name?.toLowerCase().includes(val.toLowerCase())) {
        matches.push(user);
      }
    });
    return matches;
  };

  {
    /* Function When Selecting A Client */
  }
  const handleSelectUser = (user) => {
    //   dispatch(setSelectedUser(user));
    setUserName(user?.name);

    setUserNameResult([]);
  };

  return {
    userName,
    userNameResult,
    userNameShowResult,
    screenName,
    posShow,
    posPriceChangeAuth,
    posChangeDiscount,
    posDeleteProdWithPass,
    posPassword,
    posInvoiceTypeChangeAuth,
    posPaymentMethodChangeAuth,
    posSaveNoPrintAuth,
    posEditDate,
    posChooseClient,
    posInvoiceFreeze,
    posInvoiceCall,
    posPriceChange,
    posChangeTax,
    posInvoiceCancel,
    posShiftClose,
    errors,
    adShow,
    notificationShow,
    bromocodeShow,
    WarehouseName,
    warehouseShow,
    warehouseEdit,
    warehouseDelete,
    createUser,
    configUpdate,
    productCreate,
    productEdit,
    productDelete,
    productShowSidebar,
    productShowGeneral,
    sectionCreate,
    sectionEdit,
    sectionDelete,
    sectionShowSidebar,
    orderShow,
    orderPrepare,
    orderCancel,
    orderFinish,
    orderShipment,
    orderShowSidebar,
    reportShow,
    deliveryShow,
    deliveryEdit,
    deliveryDelete,
    deliveryAddArea,
    deliveryFreeDelivery,
    kitchenShow,
    supplierShow,
    unitShow,
    customerShow,
    customerShowMessages,
    statisticsShow,
    evaluationShow,
    aboutUsShow,

    setUserName,
    setUserNameResult,
    setUserNameShowResult,
    setScreenName,
    setPosShow,
    setPosPriceChangeAuth,
    setPosChangeDiscount,
    setPosDeleteProdWithPass,
    setPosPassword,
    setPosInvoiceTypeChangeAuth,
    setPosPaymentMethodChangeAuth,
    setPosSaveNoPrintAuth,
    setPosEditDate,
    setPosChooseClient,
    setPosInvoiceFreeze,
    setPosInvoiceCall,
    setPosPriceChange,
    setPosChangeTax,
    setPosInvoiceCancel,
    setPosShiftClose,
    setErrors,
    setAdShow,
    setNotificationShow,
    setBromocodeShow,
    setWarehouseName,
    setWarehouseShow,
    setWarehouseEdit,
    setWarehouseDelete,
    setCreateUser,
    setConfigUpdate,
    setProductCreate,
    setProductEdit,
    setProductDelete,
    setProductShowSidebar,
    setProductShowGeneral,
    setSectionCreate,
    setSectionEdit,
    setSectionDelete,
    setSectionShowSidebar,
    setOrderShow,
    setOrderPrepare,
    setOrderCancel,
    setOrderFinish,
    setOrderShipment,
    setOrderShowSidebar,
    setReportShow,
    setDeliveryShow,
    setDeliveryEdit,
    setDeliveryDelete,
    setDeliveryAddArea,
    setDeliveryFreeDelivery,
    setKitchenShow,
    setSupplierShow,
    setUnitShow,
    setCustomerShow,
    setCustomerShowMessages,
    setStatisticsShow,
    setEvaluationShow,
    setAboutUsShow,

    handleSearchUserName,
    handleSelectUser,
  };
}
