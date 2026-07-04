import { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { fetchWarehouseNames } from "../../store/reducers/settingSlice";

export default function useScreenSettingsPreferenceLogic() {
  const mainWarehouse = useSelector((state) => state?.setting?.mainWarehouse);

  const savedScreenSetting = JSON.parse(
    localStorage.getItem("Screens Settings"),
  );
  const defaultWarehouseSetting = JSON.parse(
    localStorage.getItem("Invoice Settings"),
  );
  const newDefaultWarehouseSetting = JSON.parse(
    localStorage.getItem("Screens Settings"),
  );

  const users = useSelector((state) => state.user?.users);
  const permissions = useSelector((state) => state?.setting?.permissions);

   const wareHouseNames = useSelector((state)=> state?.setting?.warehouseNames);
  const dispatch = useDispatch()

  useEffect(()=>{
    dispatch(fetchWarehouseNames());
  }, [dispatch])

  const matchedStorageDefault = wareHouseNames.find(
    (item) => item.name === savedScreenSetting?.WarehouseName,
  );

  const [userName, setUserName] = useState(savedScreenSetting?.userName || "");
  const [userId, setUserId] = useState(savedScreenSetting?.userId || "");
  const [defaultWarehouseNameUser, setDefaultWarehouseNameUser] = useState(
    savedScreenSetting?.WarehouseName ||
      defaultWarehouseSetting?.defaultWarehouseName ||
      "",
  );

  const [defauldWareHouseId, setDefauldWareHouseId] = useState(
    matchedStorageDefault ? matchedStorageDefault.id : 1,
  );

  const [userNameResult, setUserNameResult] = useState("");
  const [userNameShowResult, setUserNameShowResult] = useState(false);
  const [screenName, setScreenName] = useState("pos_screen");
  const [posShow, setPosShow] = useState(savedScreenSetting?.posShow ?? true);
  const [posPriceChangeAuth, setPosPriceChangeAuth] = useState(
    savedScreenSetting?.posPriceChangeAuth ?? true,
  );
  const [posChangeDiscount, setPosChangeDiscount] = useState(
    savedScreenSetting?.posChangeDiscount ?? true,
  );
  const [posDeleteProdWithPass, setPosDeleteProdWithPass] = useState(
    savedScreenSetting?.posDeleteProdWithPass ?? true,
  );
  const [posPassword, setPosPassword] = useState("");
  const [posInvoiceTypeChangeAuth, setPosInvoiceTypeChangeAuth] = useState(
    savedScreenSetting?.posInvoiceTypeChangeAuth ?? true,
  );
  const [posPaymentMethodChangeAuth, setPosPaymentMethodChangeAuth] = useState(
    savedScreenSetting?.posPaymentMethodChangeAuth ?? true,
  );
  const [posSaveNoPrintAuth, setPosSaveNoPrintAuth] = useState(
    savedScreenSetting?.posSaveNoPrintAuth ?? true,
  );
  const [posEditDate, setPosEditDate] = useState(
    savedScreenSetting?.posEditDate ?? true,
  );
  const [posChooseClient, setPosChooseClient] = useState(
    savedScreenSetting?.posChooseClient ?? true,
  );
  const [posInvoiceFreeze, setPosInvoiceFreeze] = useState(
    savedScreenSetting?.posInvoiceFreeze ?? true,
  );
  const [posInvoiceCall, setPosInvoiceCall] = useState(
    savedScreenSetting?.posInvoiceCall ?? true,
  );
  const [posPriceChange, setPosPriceChange] = useState(
    savedScreenSetting?.posPriceChange ?? true,
  );
  const [posChangeTax, setPosChangeTax] = useState(
    savedScreenSetting?.posChangeTax ?? true,
  );
  const [posInvoiceCancel, setPosInvoiceCancel] = useState(
    savedScreenSetting?.posInvoiceCancel ?? true,
  );
  const [posShiftClose, setPosShiftClose] = useState(
    savedScreenSetting?.posShiftClose ?? true,
  );
  const [adShow, setAdShow] = useState(savedScreenSetting?.adShow ?? true);
  const [notificationShow, setNotificationShow] = useState(
    savedScreenSetting?.notificationShow ?? true,
  );
  const [bromocodeShow, setBromocodeShow] = useState(
    savedScreenSetting?.bromocodeShow ?? true,
  );
  const [WarehouseName, setWarehouseName] = useState(
    savedScreenSetting?.WarehouseId ||
      wareHouseNames?.find((wh)=>wh?.is_default === true)?.name ||
      defaultWarehouseSetting?.defaultWarehouseName ||
      "مخزن 1",
  );
  const [warehouseShow, setWarehouseShow] = useState(
    savedScreenSetting?.warehouseShow ?? true,
  );
  const [warehouseEdit, setWarehouseEdit] = useState(
    savedScreenSetting?.warehouseEdit ?? true,
  );
  const [warehouseDelete, setWarehouseDelete] = useState(
    savedScreenSetting?.warehouseDelete ?? true,
  );

  const [allowedWareHouseName, setAllowedWareHouseName] = useState(() => {
  const saved = JSON.parse(localStorage.getItem("Screens Settings"));
  if (saved && saved.allowedWareHouseName && saved.allowedWareHouseName.length > 0) {
    return saved.allowedWareHouseName;
  }
  const defaultWarehouse = wareHouseNames?.find((wh)=>wh?.is_default === true)
  return [{ id: defaultWarehouse?.id, name: defaultWarehouse?.name }];
});

  const [createUser, setCreateUser] = useState(
    savedScreenSetting?.createUser ?? true,
  );
  const [configUpdate, setConfigUpdate] = useState(
    savedScreenSetting?.configUpdate ?? true,
  );
  const [productCreate, setProductCreate] = useState(
    savedScreenSetting?.productCreate ?? true,
  );
  const [productEdit, setProductEdit] = useState(
    savedScreenSetting?.productEdit ?? true,
  );
  const [productDelete, setProductDelete] = useState(
    savedScreenSetting?.productDelete ?? true,
  );
  const [productShowSidebar, setProductShowSidebar] = useState(
    savedScreenSetting?.productShowSidebar ?? true,
  );
  const [productShowGeneral, setProductShowGeneral] = useState(
    savedScreenSetting?.productShowGeneral ?? true,
  );
  const [sectionCreate, setSectionCreate] = useState(
    savedScreenSetting?.sectionCreate ?? true,
  );
  const [sectionEdit, setSectionEdit] = useState(
    savedScreenSetting?.sectionEdit ?? true,
  );
  const [sectionDelete, setSectionDelete] = useState(
    savedScreenSetting?.sectionDelete ?? true,
  );
  const [sectionShowSidebar, setSectionShowSidebar] = useState(
    savedScreenSetting?.sectionShowSidebar ?? true,
  );
  const [orderShow, setOrderShow] = useState(
    savedScreenSetting?.orderShow ?? true,
  );
  const [orderPrepare, setOrderPrepare] = useState(
    savedScreenSetting?.orderPrepare ?? true,
  );
  const [orderCancel, setOrderCancel] = useState(
    savedScreenSetting?.orderCancel ?? true,
  );
  const [orderShipment, setOrderShipment] = useState(
    savedScreenSetting?.orderShipment ?? true,
  );
  const [orderFinish, setOrderFinish] = useState(
    savedScreenSetting?.orderFinish ?? true,
  );
  const [orderShowSidebar, setOrderShowSidebar] = useState(
    savedScreenSetting?.orderShowSidebar ?? true,
  );
  const [reportShow, setReportShow] = useState(
    savedScreenSetting?.reportShow ?? true,
  );
  const [deliveryShow, setDeliveryShow] = useState(
    savedScreenSetting?.deliveryShow ?? true,
  );
  const [deliveryEdit, setDeliveryEdit] = useState(
    savedScreenSetting?.deliveryEdit ?? true,
  );
  const [deliveryDelete, setDeliveryDelete] = useState(
    savedScreenSetting?.deliveryDelete ?? true,
  );
  const [deliveryAddArea, setDeliveryAddArea] = useState(
    savedScreenSetting?.deliveryAddArea ?? true,
  );
  const [deliveryFreeDelivery, setDeliveryFreeDelivery] = useState(
    savedScreenSetting?.deliveryFreeDelivery ?? true,
  );
  const [kitchenShow, setKitchenShow] = useState(
    savedScreenSetting?.kitchenShow ?? true,
  );
  const [supplierShow, setSupplierShow] = useState(
    savedScreenSetting?.supplierShow ?? true,
  );
  const [unitShow, setUnitShow] = useState(
    savedScreenSetting?.unitShow ?? true,
  );
  const [customerShow, setCustomerShow] = useState(
    savedScreenSetting?.customerShow ?? true,
  );
  const [customerShowMessages, setCustomerShowMessages] = useState(
    savedScreenSetting?.customerShowMessages ?? true,
  );
  const [statisticsShow, setStatisticsShow] = useState(
    savedScreenSetting?.statisticsShow ?? true,
  );
  const [evaluationShow, setEvaluationShow] = useState(
    savedScreenSetting?.evaluationShow ?? true,
  );
  const [aboutUsShow, setAboutUsShow] = useState(
    savedScreenSetting?.aboutUsShow ?? true,
  );

  const [isPasswordSent, setIsPasswordSent] = useState(
    savedScreenSetting?.isPasswordSent ?? false,
  );
  const [errors, setErrors] = useState({
    userName: "",
    printerName: "",
    password: "",
  });

  const [viewablePermissions, setViewablePermissions] = useState(savedScreenSetting?.viewablePermissions ?? []);

  useEffect(() => {
    if (newDefaultWarehouseSetting?.WarehouseName) {
      const selectedWarehouse = wareHouseNames.find(
        (method) => method.name === newDefaultWarehouseSetting?.WarehouseName,
      );
      if (selectedWarehouse) {
        setDefauldWareHouseId(selectedWarehouse.id);
      }
    }
  }, []);

  const [warehousePermissions, setWarehousePermissions] = useState(
    savedScreenSetting?.warehousePermissions || [
      {
        warehouseId: 3,
        warehouseName: "مخزن 1",
        canIn: false,
        canOut: false,
        canTransfer: false,
        canAdjust: false,
      },
      {
        warehouseId: 2,
        warehouseName: "مخزن 2",
        canIn: false,
        canOut: false,
        canTransfer: false,
        canAdjust: false,
      },
      {
        warehouseId: 3,
        warehouseName: "مخزن 3",
        canIn: false,
        canOut: false,
        canTransfer: false,
        canAdjust: false,
      },
      {
        warehouseId: 4,
        warehouseName: "مخزن 4",
        canIn: false,
        canOut: false,
        canTransfer: false,
        canAdjust: false,
      },
      {
        warehouseId: 5,
        warehouseName: "مخزن 5",
        canIn: false,
        canOut: false,
        canTransfer: false,
        canAdjust: false,
      },
    ],
  );

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

  const handleSelectUser = (user) => {
    setUserName(user);
    setUserNameResult([]);
  };

  

  return {
    userName,
    userId,
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
    isPasswordSent,
    allowedWareHouseName,
    defauldWareHouseId,
    warehousePermissions,
    defaultWarehouseNameUser,
    viewablePermissions,

    setUserName,
    setUserId,
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
    setIsPasswordSent,
    setAllowedWareHouseName,
    setDefauldWareHouseId,
    setWarehousePermissions,
    setDefaultWarehouseNameUser,
    setViewablePermissions,

    handleSearchUserName,
    handleSelectUser,
  };
}
