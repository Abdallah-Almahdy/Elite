import React, { useEffect, useState } from "react";
import UserSettings from "../components/settings/screens/UserSettings";
import ScreenCard from "../components/ui/screens/ScreenCard";
import { useSelectedScreens } from "../contexts/SelectedScreensContext";
import { useScreensPermissions } from "../contexts/ScreensPermissionsContext";
import { useSelector, useDispatch } from "react-redux";
import { sendPermissions } from "../store/reducers/settingSlice";
import notify from "../hooks/Notification";
import Pagination from "../components/ui/Pagination";
import useScreenSettingsPreferenceLogic from "../hooks/settings/useScreenSettingsPreferenceLogic";
import { fetchAdmin } from "../store/reducers/adminSlice";
import { useInvoiceSettings } from "../contexts/InvoiceSettingsContext";

export default function ScreensHome() {
  const admin = useSelector((state) => state?.admin?.admin); 
  const {updateScreenSettings} = useInvoiceSettings();
  const {  userName,
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
    aboutUsShow, } = useScreenSettingsPreferenceLogic();
  const screens = [
    { id: 1, name: "شاشة البيع" },
    { id: 2, name: "شاشة الدليفرى" },
    { id: 3, name: "شاشة الاعلانات" },
    { id: 4, name: "شاشة الاشعارات" },
    { id: 5, name: "شاشة البروموكود" },
    { id: 6, name: "شاشة المخازن" },
    { id: 7, name: "شاشة انشاء مستخدم" },
    { id: 8, name: "شاشة الاعدادات" },
    { id: 9, name: "شاشة المنتجات" },
    { id: 10, name: "شاشة الاقسام" },
    { id: 11, name: "شاشة الطلبيات" },
    { id: 12, name: "شاشة التقارير" },
    { id: 13, name: "شاشة المطابخ" },
    { id: 14, name: "شاشة الموردين" },
    { id: 15, name: "شاشة الوحدات" },
    { id: 16, name: "شاشة العملاء" },
    { id: 17, name: "شاشة شكاوى العملاء" },
    { id: 18, name: "شاشة الاحصائيات" },
    { id: 19, name: "شاشة التقييمات " },
    { id: 20, name: "عننا" },
  ];
  const loading = useSelector((state) => state?.setting?.loading);
  const dispatch = useDispatch();


  const { selectedScreens, setSelectedScreens } = useSelectedScreens();
  const { screenSettings, setScreenSettings } = useScreensPermissions();
  // const savedCurrentPage = JSON.parse(sessionStorage.getItem("CurrentPage for Pagination"))

  useEffect(()=>{
    if(!selectedScreens["شاشة البيع"]){
     const payload = {
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
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة الدليفرى"]){
     const payload = {
        deliveryShow: false,
        deliveryEdit: false,
        deliveryDelete: false,
        deliveryAddArea: false,
        deliveryFreeDelivery: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة الاعلانات"]){
     const payload = {
        adShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة الاشعارات"]){
     const payload = {
        notificationShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة البروموكود"]){
     const payload = {
       bromocodeShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة المخازن"]){
     const payload = {
        WarehouseName: "",
        warehouseShow: false,
        warehouseEdit: false,
        warehouseDelete: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة انشاء مستخدم"]){
     const payload = {
       createUser: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة الاعدادات"]){
     const payload = {
        configUpdate: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة المنتجات"]){
     const payload = {
        productCreate: false,
        productEdit: false,
        productDelete: false,
        productShowSidebar: false,
        productShowGeneral: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة الاقسام"]){
     const payload = {
        sectionCreate: false,
        sectionEdit: false,
        sectionDelete: false,
        sectionShowSidebar: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة الطلبيات"]){
     const payload = {
         orderShow: false,
        orderPrepare: false,
        orderCancel: false,
        orderFinish: false,
        orderShipment: false,
        orderShowSidebar: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة التقارير"]){
     const payload = {
        reportShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة المطابخ"]){
     const payload = {
       kitchenShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة الاحصائيات"]){
     const payload = {
        statisticsShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة الموردين"]){
     const payload = {
        supplierShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة الوحدات"]){
     const payload = {
        unitShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة العملاء"]){
     const payload = {
        customerShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة شكاوى العملاء"]){
     const payload = {
        customerShowMessages: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["شاشة التقييمات "]){
     const payload = {
        evaluationShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
    if(!selectedScreens["عننا"]){
     const payload = {
        aboutUsShow: false,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    }
  }, [selectedScreens,
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
    aboutUsShow,])

    useEffect(() => {
        dispatch(fetchAdmin());
      }, [dispatch]);

  useEffect(() => {
    sessionStorage.setItem("Selected Screens", JSON.stringify(selectedScreens));
  }, [selectedScreens]);
  useEffect(() => {
    localStorage.setItem("Screens Settings", JSON.stringify(screenSettings));
  }, [screenSettings]);
  const handleSavePermissions = async () => {
    try {
      const payload = {
        user_id: admin?.id,
        permissions: {
          "config.update": screenSettings?.configUpdate,
          "delevary.addArea": screenSettings?.deliveryAddArea,
          "delevary.freeDelevary": screenSettings?.deliveryFreeDelivery,
          "delivery.delete": screenSettings?.deliveryDelete,
          "delivery.edit": screenSettings?.deliveryEdit,
          "order.cancel": screenSettings?.orderCancel,
          "order.finish": screenSettings?.orderFinish,
          "order.prepare": screenSettings?.orderPrepare,
          "order.shipment": screenSettings?.orderShipment,
          "order.show": screenSettings?.orderShow,
          "pos.changeDiscount": screenSettings?.posChangeDiscount,
          "pos.changeTax": screenSettings?.posChangeTax,
          "pos.chooseClient": screenSettings?.posChooseClient,
          "pos.deleteProdWithPass": screenSettings?.posDeleteProdWithPass,
          "pos.editDate": screenSettings?.posEditDate,
          "pos.InviceCall": screenSettings?.posInvoiceCall,
          "pos.InviceCancel": screenSettings?.posInvoiceCancel,
          "pos.InviceFreeze": screenSettings?.posInvoiceFreeze,
          "pos.InvoiceTypeChangeAuth": screenSettings?.posInvoiceTypeChangeAuth,
          "pos.paymentMethodChangeAuth":
            screenSettings?.posPaymentMethodChangeAuth,
          "pos.priceChange": screenSettings?.posPriceChange,
          "pos.priceChangeAuth": screenSettings?.posPriceChangeAuth,
          "pos.saveNoPrintAuth": screenSettings?.posSaveNoPrintAuth,
          "pos.shiiftClose": screenSettings?.posShiftClose,
          "pos.show": screenSettings?.posShow,
          "product.create": screenSettings?.productCreate,
          "product.delete": screenSettings?.productDelete,
          "product.edit": screenSettings?.productEdit,
          "reports.show": screenSettings?.reportShow,
          "section.create": screenSettings?.sectionCreate,
          "section.delete": screenSettings?.sectionDelete,
          "section.edit": screenSettings?.sectionEdit,
          showAds: screenSettings?.adShow,
          showDelevary: screenSettings?.deliveryShow,
          showGenralProducts: screenSettings?.productShowGeneral,
          showNotifications: screenSettings?.notificationShow,
          showOrdersSidebar: screenSettings?.orderShowSidebar,
          showProductsSidebar: screenSettings?.productShowSidebar,
          showPromoCodes: screenSettings?.bromocodeShow,
          showSectionsSidebar: screenSettings?.sectionShowSidebar,
          "user.create": screenSettings?.createUser,
          "warehouse.delete": screenSettings?.warehouseDelete,
          "warehouse.edit": screenSettings?.warehouseEdit,
          "warehouse.show": screenSettings?.warehouseShow,
          showAboutUs: screenSettings?.aboutUsShow,
          showClientsVote: screenSettings?.evaluationShow,
          showCustomers: screenSettings?.customerShow,
          showCustomersMessages: screenSettings?.customerShowMessages,
          showKitchen: screenSettings?.kitchenShow,
          showStatistics: screenSettings?.statisticsShow,
          showSuppliers: screenSettings?.supplierShow,
          showUnits: screenSettings?.unitShow,
        },
      };
      await dispatch(sendPermissions({ permissions: payload })).unwrap();
      notify("تم حفظ الاعدادات بنجاح", "success");
      localStorage.removeItem("Screens Settings");
      sessionStorage.removeItem("Selected Screens");
      setScreenSettings({
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
      });

      setSelectedScreens({
        "شاشة البيع": false,
        "شاشة الاعلانات": false,
        "شاشة الاشعارات": false,
        "شاشة البروموكود": false,
        "شاشة المخازن": false,
        "انشاء مستخدم": false,
        الاعدادات: false,
        المنتجات: false,
        الاقسام: false,
        الطلبيات: false,
        التقارير: false,
        "شاشة المطابخ": false,
        "شاشة الاحصائيات": false,
        "شاشة الموردين": false,
        "شاشة الوحدات": false,
        "شاشة العملاء": false,
        "شاشة شكاوى العملاء": false,
        "شاشة التقييمات ": false,
        عننا: false,
      });
    } catch (error) {
      notify("حدثت مشكله برجاء المحاوله مرة أخرى", "error");
    }
  };
  //  const totalPages = Math.ceil(screens.length / 12);
  // const [currentPage, setCurrentPage] = useState(savedCurrentPage || 1);
  // const startIndex = (currentPage - 1) * 12;
  // const visibleScreens = screens.slice(
  //   startIndex,
  //   startIndex + 12,
  // );

  //   useEffect(()=>{
  // sessionStorage.setItem("CurrentPage for Pagination", JSON.stringify(currentPage));
  //   },[currentPage])

  return (
    <div className="w-full min-h-screen bg-gray-100 pt-12 flex flex-col gap-y-5">
      {/* Cashier Name */}
      <div className="w-[50%] mx-auto">
        <UserSettings />
      </div>

      {/* Screens View */}
      <div className="w-[95%] mx-auto flex flex-col justify-center items-center">
        <h1 className="text-2xl font-bold mb-5">الشاشات المتاحه</h1>
        <div className="w-full grid grid-cols-4 gap-3">
          <ScreenCard screens={screens} />
        </div>
        {/* {screens.length > 12 && (
              <div className='w-full flex items-start justify-start mt-2'>
              <Pagination
      currentPage={currentPage}
      totalPages={totalPages}
      onPageChange={(page)=> setCurrentPage(page)}
      />
             </div>
             )} */}
      </div>
      {/* <div className='w-[95%] flex justify-end items-center'>
    <button
          className="bg-blue-700 hover:bg-blue-600 text-white px-5 p-2 rounded-xl font-semibold transition-colors duration-200 shadow-md hover:shadow-lg -mt-5"
          onClick={handleSavePermissions}
        >
            {loading ? <span className="loader"></span>
 : `حفظ الإعدادات`}
          
        </button>
        </div> */}
      <div className="w-[95%] mx-auto mt-auto flex justify-end items-center pb-4">
        {/* {screens.length > 12 ? (
    <Pagination
      currentPage={currentPage}
      totalPages={totalPages}
      onPageChange={setCurrentPage}
    />
  ) : (
    <div />   
  )} */}

        <button
          className="bg-blue-700 hover:bg-blue-600 text-white px-5 p-2 rounded-xl font-semibold transition-colors duration-200 shadow-md hover:shadow-lg"
          onClick={handleSavePermissions}
        >
          {loading ? <span className="loader"></span> : "حفظ الإعدادات"}
        </button>
      </div>
    </div>
  );
}
