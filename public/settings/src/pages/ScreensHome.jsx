import React, { useEffect, useState } from "react";
import UserSettings from "../components/settings/screens/UserSettings";
import ScreenCard from "../components/ui/screens/ScreenCard";
import { useSelectedScreens } from "../contexts/SelectedScreensContext";
import { useScreensPermissions } from "../contexts/ScreensPermissionsContext";
import { useSelector, useDispatch } from "react-redux";
import {
  fetchPermissions,
  fetchUserPermissions,
  sendPermissions,
} from "../store/reducers/settingSlice";
import notify from "../hooks/Notification";
import Pagination from "../components/ui/Pagination";
import useScreenSettingsPreferenceLogic from "../hooks/settings/useScreenSettingsPreferenceLogic";
import { fetchAdmin } from "../store/reducers/adminSlice";
import { useInvoiceSettings } from "../contexts/InvoiceSettingsContext";
import { FaUserCog } from "react-icons/fa";
import useSettingsPreferenceLogic from "../hooks/settings/useSettingsPreferenceLogic";
import LoadingSpinner from "../components/ui/common/LoadingSpinner";

export default function ScreensHome() {
  const [loading, setLoading] = useState(false);
  const admin = useSelector((state) => state?.admin?.admin);
  //const permissions = useSelector((state) => state?.setting?.permissions);
  const userPermissions = useSelector(
    (state) => state?.setting?.userPermissions,
  );
  const loadingPage = useSelector(
    (state) => state?.user?.loading,
  );
  const {
    userName,
    errors,
    setUserName,
    userId,
    setUserId,
    userNameShowResult,
    setPosShow,
  } = useScreenSettingsPreferenceLogic();
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
  // const loading = useSelector((state) => state?.setting?.loading);
  const dispatch = useDispatch();

  const { selectedScreens, setSelectedScreens } = useSelectedScreens();
  const { screenSettings, setScreenSettings } = useScreensPermissions();

  // useEffect(() => {
  //   if (userName) {
  //     dispatch(fetchUserPermissions({ id: screenSettings?.userId }));
  //   }
  // }, [dispatch, screenSettings?.userId, userName]);

  useEffect(() => {
    dispatch(fetchAdmin());
  }, [dispatch, screenSettings?.userId]);
  // useEffect(() => {
  //   const loadPermissions = async () => {
  //     //await dispatch(fetchUserPermissions({id: screenSettings?.userId}));
  //   };

  //   loadPermissions();
  // }, [dispatch, screenSettings?.userId]);

  // useEffect(() => {
  //   if (!userPermissions) return;

  //   setPosShow(userPermissions["pos.show"])
  //   // setPosPriceChangeAuth,
  //   // setPosChangeDiscount,
  //   // setPosDeleteProdWithPass,
  //   // setPosPassword,
  //   // setPosInvoiceTypeChangeAuth,
  //   // setPosPaymentMethodChangeAuth,
  //   // setPosSaveNoPrintAuth,
  //   // setPosEditDate,
  //   // setPosChooseClient,
  //   // setPosInvoiceFreeze,
  //   // setPosInvoiceCall,
  //   // setPosPriceChange,
  //   // setPosChangeTax,
  //   // setPosInvoiceCancel,
  //   // setPosShiftClose,
  // }, [userPermissions, setPosShow]);

  useEffect(() => {
    sessionStorage.setItem("Selected Screens", JSON.stringify(selectedScreens));
  }, [selectedScreens]);
  // useEffect(() => {
  //   localStorage.setItem("Screens Settings", JSON.stringify(screenSettings));
  // }, [screenSettings]);
  const handleSavePermissions = async () => {
    try {
      if (
        (screenSettings.posDeleteProdWithPass == true &&
          screenSettings?.errors.password.length == 0 &&
          screenSettings?.isPasswordSent) ||
        !screenSettings.posDeleteProdWithPass
      ) {
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
            "pos.InvoiceTypeChangeAuth":
              screenSettings?.posInvoiceTypeChangeAuth,
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
        setLoading(true);
        await dispatch(sendPermissions({ permissions: payload })).unwrap();
        // await dispatch(fetchPermissions())
        notify("تم حفظ الاعدادات بنجاح", "success");
        setLoading(false);
      } else {
        notify("يرجى كتابة كلمة سر صحيحة وارسالها فى شاشة البيع", "error");
      }
    } catch (error) {
      notify("حدثت مشكله برجاء المحاوله مرة أخرى", "error");
    }
  };

  return (
    <div className="w-full min-h-screen bg-gray-100 pt-12 flex flex-col gap-y-5">
      {/* Cashier Name */}
      {loadingPage ? (
        <div className="w-full h-[90vh] flex justify-center items-center">
          <LoadingSpinner />
        </div>
      ) : (
        <>
        <div className="w-[55%] mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-6 animate-fadeIn mb-5">
        <div className="flex items-center gap-2 mb-5 text-slate-800 font-bold border-b border-slate-100 pb-3">
          <FaUserCog className="text-blue-600" />
          <h2>بيانات المستخدم الأساسية</h2>
        </div>
        <UserSettings
          userName={userName}
          setUserName={setUserName}
          userId={userId}
          setUserId={setUserId}
          userNameShowResult={userNameShowResult}
          errors={errors}
        />
      </div>

      {/* Screens View */}
      <div className="w-[95%] mx-auto flex flex-col justify-center items-center">
        <h1 className="text-2xl font-bold mb-5">الشاشات المتاحه</h1>
        <div className="w-full grid grid-cols-4 gap-3">
          <ScreenCard screens={screens} />
        </div>
      </div>

      <div className="w-[95%] mx-auto mt-auto flex justify-end items-center pb-4">
        <button
          className="bg-blue-700 hover:bg-blue-600 text-white px-5 p-2 rounded-xl font-semibold transition-colors duration-200 shadow-md hover:shadow-lg"
          onClick={handleSavePermissions}
        >
          {loading ? <span className="loader"></span> : "حفظ الإعدادات"}
        </button>
      </div>
        </>
      )}
    </div>
  );
}
