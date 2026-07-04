import React, { useState, useEffect } from "react";
import { useSelector, useDispatch } from "react-redux";
import { useScreensPermissions } from "../contexts/ScreensPermissionsContext";
import useScreenSettingsPreferenceLogic from "../hooks/settings/useScreenSettingsPreferenceLogic";
import PrinterSettingsPage from "./PrinterSettingsPage";
import WarehouseSettings from "../components/settings/screens/WarehouseSettings";
import UserSettings from "../components/settings/screens/UserSettings";
import {
  FaSlidersH,
  FaPrint,
  FaWarehouse,
  FaCogs,
  FaUserCog,
  FaSave,
} from "react-icons/fa";
import qz from "qz-tray";

import {
  fetchInvoicePrintersConfig,
  fetchUserPrintersConfig,
  fetchUserSectionsConfig,
  fetchUserWarehouseConfig,
  sendConfigs,
  sendInvoicePrintersConfig,
  sendUserSectionsConfig,
  sendUserWarehouseConfig,
} from "../store/reducers/settingSlice";
import { useInvoiceSettings } from "../contexts/InvoiceSettingsContext";
import notify from "../hooks/Notification";
import { fetchClientsNames } from "../store/reducers/userSlice";
import { useUserSettingsPreference } from "../contexts/UserSettingsPreferenceContext";
import { useSettingsPreference } from "../contexts/SettingsPreferenceContext";
import InvoiceType from "../components/settings/general/InvoiceTypeSettings";
import PaymentSettings from "../components/settings/general/PaymentSettings";
import TaxSettings from "../components/settings/general/TaxSettings";
import SectionsPrinterSettings from "../components/settings/printer/SectionsPrinterSettings";
import { FaSection } from "react-icons/fa6";
import { FaFileInvoice } from "react-icons/fa";
import { BiSolidCategory } from "react-icons/bi";
import { getPrinters } from "../services/qzService";
import InvoiceTypeSettings from "../components/settings/general/InvoiceTypeSettings";

export default function UserSettingsPage() {
  const mainWarehouse = useSelector((state) => state?.setting?.mainWarehouse);
  const users = useSelector((state) => state?.user?.users);
  const userWarehouseConfig = useSelector(
    (state) => state?.setting?.userWarehouseConfig,
  );

  const userPrintersConfig = useSelector(
    (state) => state?.setting?.userPrintersConfig,
  );
  const invoicePrintersConfig = useSelector(
    (state) => state?.setting?.invoicePrintersConfig,
  );
  const userSectionsConfig = useSelector(
    (state) => state?.setting?.userSectionsConfig,
  );

  const categories = useSelector((state) => state?.product?.categories || []);

  const [availablePrinters, setAvailablePrinters] = useState([]);

  const dispatch = useDispatch();
  const [activeTab, setActiveTab] = useState("invoice");

  const { screenSettings, setScreenSettings } = useScreensPermissions();
  const { updatePrinterSettings } = useInvoiceSettings();

  const { defaultWarehouseName, setDefaultWarehouseName } =
    useSettingsPreference();

  const currentViewablePermissions = screenSettings?.viewablePermissions || [];
  const currentViewableSectionsPermissions =
    screenSettings?.viewableSectionsPermissions || [];

  const { WarehouseName, setWarehouseName } =
    useScreenSettingsPreferenceLogic();

  const { updateUserSettings } = useInvoiceSettings();

  const paymentMapping = {
    كاش: "cash",
    "بطاقة ائتمان": "credit_card",
    "انستا باى": "instapay",
    محفظة: "wallet",
    اجل: "remaining",
  };
  const invoiceMapping = {
    "تيك أواى": "take_away",
    دليفرى: "delvery",
    صالة: "hall",
  };
  const TaxMapping = {
    "%": "%",
    "ج.م": "pound",
  };

  const {
    userName,
    userId,
    setUserId,
    setUserName,
    userNameShowResult,
    userPrinterName,
    saveNoPrintAuth,
    sectionName,
    sectionId,
    sectionPrinterName,
    barcodePrinterName,
    reportPrinterName,
    setUserPrinterName,
    setSaveNoPrintAuth,
    setSectionName,
    setSectionId,
    setSectionPrinterName,
    setBarcodePrinterName,
    setReportPrinterName,
    receiptPrinterName,
    invoicePrinterName,
    receiptPrintAuth,
    invoicePrintAuth,
    receiptModelName,
    invoiceModelName,
    receiptNum,
    invoiceNum,
    setReceiptPrinterName,
    setInvoicePrinterName,
    setReceiptPrintAuth,
    setInvoicePrintAuth,
    setReceiptModelName,
    setInvoiceModelName,
    setReceiptNum,
    setInvoiceNum,
    defaultInvoiceType,
    allowedInvoiceType,
    defaultPaymentMethod,
    allowedPaymentMethods,
    applyTax,
    taxType,
    taxValue,
    setDefaultInvoiceType,
    setAllowedInvoiceType,
    setDefaultPaymentMethod,
    setAllowedPaymentMethods,
    setApplyTax,
    setTaxType,
    setTaxValue,
    sectionRows,
    setSectionRows,
    errors,

    handleInvoiceUserCancel,
  } = useUserSettingsPreference();

  const wareHouseNames = useSelector((state) => state?.setting?.warehouseNames);

  const mappedAllowedInvoiceType = allowedInvoiceType.map((type) => {
    return invoiceMapping[type];
  });
  const mappedAllowedPaymentMethods = allowedPaymentMethods.map((type) => {
    return paymentMapping[type];
  });

  const wareHouseNamesMapping = wareHouseNames.map((w) => ({
    id: w?.id,
    name: w?.name,
  }));

  useEffect(() => {
    if (
      categories &&
      categories.length > 0 &&
      currentViewableSectionsPermissions.length === 0
    ) {
      const allCategoryIds = categories.map((cat) => cat.id);

      setScreenSettings((prev) => ({
        ...prev,
        viewableSectionsPermissions: allCategoryIds,
        allowedSectionsNamesPOS: categories.filter((cat) =>
          allCategoryIds.includes(cat?.id),
        ),
      }));
    }
  }, [
    categories,
    currentViewableSectionsPermissions.length,
    setScreenSettings,
  ]);

  useEffect(() => {
    const fetchPrinters = async () => {
      try {
        if (!qz.websocket.isActive()) {
          console.log("Connecting to QZ Tray...");
          await qz.websocket.connect();
          console.log("Connected to QZ Tray");
        }
        const printers = await getPrinters();
        setAvailablePrinters(printers);
      } catch (error) {
        console.error("Failed to load printers:", error);
      }
    };

    fetchPrinters();
  }, []);

  useEffect(() => {
    if (!userWarehouseConfig || userWarehouseConfig.length === 0) return;
    // const saved = JSON.parse(localStorage.getItem("Screens Settings"))
    const selectedWarehouse = userWarehouseConfig?.find(
      (w) => w?.is_default === true,
    );
    setWarehouseName(selectedWarehouse?.id);
    setScreenSettings((prev) => ({
      ...prev,
      allowedWareHouseName: userWarehouseConfig,
    }));
  }, [userWarehouseConfig]);

  useEffect(() => {
    const payload = {
      userName: userName,
      userPrinterName: userPrinterName,
      saveNoPrintAuth: saveNoPrintAuth,
      sectionName: sectionName,
      sectionId: sectionId,
      sectionPrinterName: sectionPrinterName,
      barcodePrinterName: barcodePrinterName,
      reportPrinterName: reportPrinterName,
      receiptPrinterName: receiptPrinterName,
      invoicePrinterName: invoicePrinterName,
      receiptPrintAuth: receiptPrintAuth,
      invoicePrintAuth: invoicePrintAuth,
      receiptModelName: receiptModelName,
      invoiceModelName: invoiceModelName,
      receiptNum: receiptNum,
      invoiceNum: invoiceNum,
      defaultInvoiceType: defaultInvoiceType,
      allowedInvoiceType: allowedInvoiceType,
      defaultPaymentMethod: defaultPaymentMethod,
      allowedPaymentMethods: allowedPaymentMethods,
      applyTax: applyTax,
      taxType: taxType,
      taxValue: taxValue,
      currentViewableSectionsPermissions: currentViewableSectionsPermissions,
      warehouseName: WarehouseName,
      allowedUserWarehouseNames: screenSettings?.allowedWareHouseName,
      sectionRows: sectionRows,
    };
    updateUserSettings(payload);
  }, [
    userName,
    userPrinterName,
    saveNoPrintAuth,
    sectionName,
    sectionId,
    sectionPrinterName,
    barcodePrinterName,
    reportPrinterName,
    receiptPrinterName,
    invoicePrinterName,
    receiptPrintAuth,
    invoicePrintAuth,
    receiptModelName,
    invoiceModelName,
    receiptNum,
    invoiceNum,
    defaultInvoiceType,
    allowedInvoiceType,
    defaultPaymentMethod,
    allowedPaymentMethods,
    applyTax,
    taxType,
    taxValue,
    currentViewableSectionsPermissions,
    WarehouseName,
    screenSettings?.allowedWareHouseName,
    sectionRows,
  ]);

  useEffect(() => {
    if (defaultWarehouseName && !userWarehouseConfig) {
      setWarehouseName(defaultWarehouseName);

      const matchedWarehouse = wareHouseNamesMapping?.find(
        (w) => w.name === defaultWarehouseName,
      );

      const selectedWarehouse = wareHouseNames?.find(
        (w) => w?.name === defaultWarehouseName,
      );

      setScreenSettings((prev) => {
        let currentAllowed = prev?.allowedWareHouseName || [];
        let currentAllowedSections = prev?.allowedSections || [];
        let currentPermissions = prev?.warehousePermissions || [];
        let currentViewable = prev?.viewablePermissions || [];
        let currentSectionsViewable = prev?.viewableSectionsPermissions || [];

        if (
          currentAllowed.length === 1 &&
          currentAllowed[0].id === 1 &&
          defaultWarehouseName !== "مخزن 1"
        ) {
          currentAllowed = [];
          currentAllowedSections = [];
          currentPermissions = [];
          currentViewable = [];
          currentSectionsViewable = [];
        }
        if (
          currentAllowedSections.length === 1 &&
          currentAllowedSections[0].id === 1 &&
          sectionName !== "بن عبد المعبود"
        ) {
          currentAllowed = [];
          currentAllowedSections = [];
          currentPermissions = [];
          currentViewable = [];
          currentSectionsViewable = [];
        }

        const exists = currentAllowed.some(
          (item) => item.name === defaultWarehouseName,
        );
        const sectionExists = currentAllowedSections.some(
          (item) => item.name === sectionName,
        );

        const updatedAllowed = exists
          ? currentAllowed
          : matchedWarehouse
            ? [...currentAllowed, matchedWarehouse]
            : currentAllowed;

        const updatedAllowedSections = sectionExists
          ? currentAllowedSections
          : sectionId
            ? [...currentAllowedSections, sectionId]
            : currentAllowedSections;

        const updatedPermissions = exists
          ? currentPermissions
          : matchedWarehouse
            ? [
                ...currentPermissions,
                {
                  warehouseId: matchedWarehouse.id,
                  warehouseName: matchedWarehouse.name,
                  canIn: false,
                  canOut: false,
                  canTransfer: false,
                  canAdjust: false,
                },
              ]
            : currentPermissions;

        const updatedViewable = matchedWarehouse
          ? currentViewable.includes(matchedWarehouse.id)
            ? currentViewable
            : [...currentViewable, matchedWarehouse.id]
          : currentViewable;

        const updatedSectionsViewable = sectionId
          ? currentSectionsViewable.includes(sectionId)
            ? currentSectionsViewable
            : [...currentSectionsViewable, sectionId]
          : currentSectionsViewable;

        return {
          ...prev,
          WarehouseName: defaultWarehouseName,
          WarehouseId: selectedWarehouse?.id,
          allowedWareHouseName: updatedAllowed,
          warehousePermissions: updatedPermissions,
          viewablePermissions: updatedViewable,
          viewableSectionsPermissions: updatedSectionsViewable,
        };
      });
    }
  }, [
    defaultWarehouseName,
    setWarehouseName,
    setScreenSettings,
    sectionId,
    sectionName,
    wareHouseNames,
    userWarehouseConfig,
  ]);

  useEffect(() => {
    try {
      dispatch(fetchClientsNames());
      //dispatch(fetchUserPrintersConfig());
      dispatch(fetchInvoicePrintersConfig());
      dispatch(fetchUserWarehouseConfig());
      //dispatch(fetchUserSectionsConfig());
    } catch (err) {
      console.log(err);
    }
  }, [dispatch]);

//   useEffect(() => {
//     if (!userSectionsConfig || Object.keys(userSectionsConfig).length === 0) return;
//     setScreenSettings((prev) => ({
//   ...prev,
//   allowedSectionsNames: categories?.filter((sec) =>
//     userSectionsConfig?.allowdSectionsId?.includes(sec?.id)
//   ),
//   allowedSectionsNamesPOS: categories?.filter((sec) =>
//     userSectionsConfig?.seenSectionsId?.includes(sec?.id)
//   ),

// }));
// setSectionRows(userSectionsConfig?.sectionPrinters?.map((ele)=>({
//   section_id: ele?.sectionId,
//   printer_name: ele?.printerName
// })))

//   }, [userSectionsConfig]);


  const handleSubmit = async () => {
    if (
      !allowedInvoiceType.includes(defaultInvoiceType) ||
      !allowedPaymentMethods.includes(defaultPaymentMethod)
    ) {
      notify("يحب أن تكون القيم الأفتراضية مسموحة", "error");
      return;
    }
    const payload = {
      type: "user",
      defaultInvoiceType: invoiceMapping[defaultInvoiceType],
      allowedInvoiceType: mappedAllowedInvoiceType,
      defaultPaymentMethod: paymentMapping[defaultPaymentMethod],
      allowedPaymentMethods: mappedAllowedPaymentMethods,
      defaultWarehouseName: defaultWarehouseName,
      applyTax: applyTax === false ? 0 : 1,
      taxValue: taxValue,
      taxTypes: TaxMapping[taxType],
      user_id: Number(screenSettings?.userId),
    };
    try {
      await dispatch(sendConfigs({ invoiceSettings: payload })).unwrap();
      // await dispatch(fetchConfigs());
      updateUserSettings(payload);
      notify("تم حفظ الاعدادات بنجاح", "success");
    } catch (err) {
      notify("حدثت مشكلة برجاء المحاولة مرة أخرى", "error");
    }
  };

  const printerSettingSubmit = async () => {
    const payload = {
      userName,
      userPrinterName,
      saveNoPrintAuth,
      sectionName,
      sectionPrinterName,
      barcodePrinterName,
      reportPrinterName,
      receiptPrinterName,
      invoicePrinterName,
      receiptPrintAuth,
      invoicePrintAuth,
      receiptModelName,
      invoiceModelName,
      receiptNum,
      invoiceNum,
    };

    const userPrinterConfig = {
      type: "user",
      user_id: Number(screenSettings?.userId),
      cashierPrinterName: userPrinterName,
      AllowSaveWithoutPrint: saveNoPrintAuth === true ? 1 : 0,
      barcodePrinterName: barcodePrinterName,
      reportPrinterName: reportPrinterName,
      invoicePrinterSettings: [
        {
          printerName: receiptPrinterName,
          formName: receiptModelName,
          permssionName: "Receipt Printing",
          numOfCopies: receiptNum,
          isActive: receiptPrintAuth === true ? 1 : 0,
        },
        {
          printerName: invoicePrinterName,
          formName: invoiceModelName,
          permssionName: "Invoice Printing",
          numOfCopies: invoiceNum,
          isActive: invoicePrintAuth === true ? 1 : 0,
        },
      ],
    };

    try {
      await dispatch(
        sendInvoicePrintersConfig({ invoicePrintersConfig: userPrinterConfig }),
      ).unwrap();
      updatePrinterSettings(payload);
      notify("تم حفظ الإعدادات بنجاح", "success");
    } catch (err) {
      notify("حدثت مشكلة برجاء المحاولة مرة أخرى", "error");
    }
  };
  const printerSettingCancel = async () => {
    if (
      !invoicePrintersConfig ||
      Object.keys(invoicePrintersConfig).length === 0
    )
      return;
    const config =
      userPrintersConfig && Object.keys(userPrintersConfig).length > 0
        ? userPrintersConfig
        : invoicePrintersConfig;

    const visibleReceipt = config?.invicePrinters?.find(
      (conf) => conf?.permssionName === "Receipt Printing",
    );
    const visibleInvoice = config?.invicePrinters?.find(
      (conf) => conf?.permssionName === "Invoice Printing",
    );

    setReceiptPrinterName(visibleReceipt?.printerName ?? "");
    setReceiptModelName(visibleReceipt?.formName ?? "");
    setReceiptNum(Number(visibleReceipt?.numOfCopies ?? 1));
    setReceiptPrintAuth(visibleReceipt?.isActive === 1 ? true : false);

    setInvoicePrinterName(visibleInvoice?.printerName ?? "");
    setInvoiceModelName(visibleInvoice?.formName ?? "");
    setInvoiceNum(Number(visibleInvoice?.numOfCopies ?? 1));
    setInvoicePrintAuth(visibleInvoice?.isActive === 1 ? true : false);
    setUserPrinterName(config.cashierPrinterName || "");
    setSaveNoPrintAuth(config.allowSaveWithoutPrint === 1);
    setBarcodePrinterName(config.barcodePrinterName || "");
    setReportPrinterName(config.reportPrinterName || "");

    if (users && users.length > 0 && userPrintersConfig.user_id) {
      const selectedUser = users?.find(
        (user) => Number(user?.id) === Number(userPrintersConfig.user_id),
      );
      if (selectedUser) {
        setUserName(selectedUser.name || "");
      }
    }
  };

  const warehouseSettingSubmit = async () => {
    if (
      !screenSettings?.allowedWareHouseName.some((w) => w?.id === WarehouseName)
    ) {
      notify("يجب السماح بالمخزن الافتراضى", "error");
      return;
    }
    const payload = {
      defaultWarehouseName: defaultWarehouseName,
      allowedWareHouseName: screenSettings?.allowedWareHouseName,
    };

    const userWarehouseConfig = {
      user_id: Number(screenSettings?.userId),
      warehouses: screenSettings?.allowedWareHouseName.map((ele) => ({
        id: ele?.id,
        is_default: Number(screenSettings?.WarehouseId) === ele?.id ? 1 : 0,
        warehouse_name: ele?.name,
      })),
    };
    try {
      await dispatch(
        sendUserWarehouseConfig({ userWarehouseConfig: userWarehouseConfig }),
      ).unwrap();
      updateUserSettings(payload);
      notify("تم حفظ الإعدادات بنجاح", "success");
    } catch (err) {
      notify("حدثت مشكلة برجاء المحاولة مرة أخرى", "error");
    }
  };

  const handleUserWarehouseCancel = () => {
    if (!userWarehouseConfig || userWarehouseConfig.length === 0) return;
    const selectedWarehouse = userWarehouseConfig?.find(
      (w) => w?.is_default === true,
    );
    setWarehouseName(selectedWarehouse?.id);
    setScreenSettings((prev) => ({
      ...prev,
      allowedWareHouseName: userWarehouseConfig,
    }));
  };
  const userSectionsSettingSubmit = async () => {
    const payload = {
  user_id: Number(userId),
  allowdSections: screenSettings?.allowedSectionsNames?.map(
    (ele) => ele.id
  ),
  seenSections: screenSettings?.allowedSectionsNamesPOS?.map(
    (ele) => ele.id
  ),
  sectionPrinters: sectionRows?.map((ele) => ({
    sub_sections_id: ele.section_id,
    section_name: screenSettings?.allowedSectionsNames?.find(
      (ele2) => ele2.id === ele.section_id
    )?.name,
    printer_name: ele.printer_name,
  })),
};

    try {
      await dispatch(
        sendUserSectionsConfig({ userSectionsConfig: payload }),
      ).unwrap();
      updateUserSettings(payload);
      notify("تم حفظ الإعدادات بنجاح", "success");
    } catch (err) {
      notify("حدثت مشكلة برجاء المحاولة مرة أخرى", "error");
    }
  };

  const handleUserSectionsCancel = () => {
    if (!userSectionsConfig || categories.length === 0) return;

    setScreenSettings((prev) => ({
      ...prev,
      allowedSectionsNames: categories.filter((cat) =>
        userSectionsConfig.allowdSectionsId?.includes(cat.id)
      ),
      allowedSectionsNamesPOS: categories.filter((cat) =>
        userSectionsConfig.seenSectionsId?.includes(cat.id)
      ),
    }));

    setSectionRows(
      userSectionsConfig.sectionPrinters?.map((item) => ({
        section_id: item.sectionId,
        printer_name: item.printerName,
      })) ?? []
    );
  };

  return (
    <div className="w-full min-h-screen bg-slate-50 p-4 md:p-6 lg:p-8 select-none">
      {/* ... Header & Tabs stay exactly the same ... */}
      <div className="max-w-4xl mx-auto mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-5">
        <div className="flex items-center gap-3">
          <div className="p-2.5 bg-blue-600 rounded-xl text-white shadow-md shadow-blue-100">
            <FaCogs className="text-xl" />
          </div>
          <div>
            <h1 className="text-xl font-bold text-slate-800">
              إعدادات التحكم والنظام
            </h1>
          </div>
        </div>
      </div>

      <div className="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-6 animate-fadeIn mb-7">
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

      <div className="max-w-4xl mx-auto mb-6 bg-slate-200/60 p-1 rounded-xl flex gap-1 border border-slate-200">
        <button
          onClick={() => setActiveTab("invoice")}
          className={`flex-1 flex items-center justify-center gap-2 py-2.5 text-xs font-bold rounded-lg transition-all ${
            activeTab === "invoice"
              ? "bg-white text-blue-600 shadow-xs border border-slate-100"
              : "text-slate-600 hover:text-slate-900 hover:bg-white/40"
          }`}
        >
          <FaFileInvoice className="text-sm" />
          <span> اعدادات الفاتورة</span>
        </button>

        <button
          onClick={() => setActiveTab("sections")}
          className={`flex-1 flex items-center justify-center gap-2 py-2.5 text-xs font-bold rounded-lg transition-all ${
            activeTab === "sections"
              ? "bg-white text-blue-600 shadow-xs border border-slate-100"
              : "text-slate-600 hover:text-slate-900 hover:bg-white/40"
          }`}
        >
          <BiSolidCategory className="text-sm" />
          <span>اعدادات الأقسام</span>
        </button>
        <button
          onClick={() => setActiveTab("warehouse")}
          className={`flex-1 flex items-center justify-center gap-2 py-2.5 text-xs font-bold rounded-lg transition-all ${
            activeTab === "warehouse"
              ? "bg-white text-blue-600 shadow-xs border border-slate-100"
              : "text-slate-600 hover:text-slate-900 hover:bg-white/40"
          }`}
        >
          <FaWarehouse className="text-sm" />
          <span>صلاحيات المخازن</span>
        </button>

        <button
          onClick={() => setActiveTab("printer")}
          className={`flex-1 flex items-center justify-center gap-2 py-2.5 text-xs font-bold rounded-lg transition-all ${
            activeTab === "printer"
              ? "bg-white text-blue-600 shadow-xs border border-slate-100"
              : "text-slate-600 hover:text-slate-900 hover:bg-white/40"
          }`}
        >
          <FaPrint className="text-sm" />
          <span>طابعات الفواتير والتقارير</span>
        </button>
      </div>
      <div className="max-w-4xl mx-auto">
        {activeTab === "invoice" && (
          <>
            <InvoiceTypeSettings
              defaultInvoiceType={defaultInvoiceType}
              setDefaultInvoiceType={setDefaultInvoiceType}
              allowedInvoiceType={allowedInvoiceType}
              setAllowedInvoiceType={setAllowedInvoiceType}
            />
            <PaymentSettings
              defaultPaymentMethod={defaultPaymentMethod}
              setDefaultPaymentMethod={setDefaultPaymentMethod}
              allowedPaymentMethods={allowedPaymentMethods}
              setAllowedPaymentMethods={setAllowedPaymentMethods}
            />
            <TaxSettings
              taxType={taxType}
              setTaxType={setTaxType}
              taxValue={taxValue}
              setTaxValue={setTaxValue}
              applyTax={applyTax}
              setApplyTax={setApplyTax}
            />
            <div className="w-full flex justify-between items-center border-t border-slate-100 pt-4 mt-2">
              <button
                className=" bg-gray-500 hover:bg-gray-400 text-white rounded px-7 py-1.5"
                onClick={handleInvoiceUserCancel}
              >
                {" "}
                الغاء
              </button>
              <button
                onClick={handleSubmit}
                className="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-xl px-6 py-3 shadow-md shadow-blue-100 flex items-center gap-2 transition-all transform hover:-translate-y-0.5"
              >
                <FaSave className="text-sm" />
                <span>حفظ الاعدادات</span>
              </button>
            </div>
          </>
        )}
        {activeTab === "sections" && (
          <>
            <SectionsPrinterSettings
              availablePrinters={availablePrinters}
              sectionName={sectionName}
              setSectionName={setSectionName}
              sectionPrinterName={sectionPrinterName}
              setSectionPrinterName={setSectionPrinterName}
              sectionId={sectionId}
              setSectionId={setSectionId}
              sectionRows={sectionRows}
              setSectionRows={setSectionRows}
              isUserMode={true}
              viewableSectionsPermissions={currentViewableSectionsPermissions}
              setViewableSectionsPermissions={(callback) => {
                setScreenSettings((prev) => ({
                  ...prev,
                  viewableSectionsPermissions:
                    typeof callback === "function"
                      ? callback(prev.viewableSectionsPermissions)
                      : callback,
                }));
              }}
            />
            <div className="w-full flex justify-between items-center border-t border-slate-100 pt-4 mt-2">
               <button
                className=" bg-gray-500 hover:bg-gray-400 text-white rounded px-7 py-1.5"
                onClick={handleUserSectionsCancel}
              >
                {" "}
                الغاء
              </button>
              <button
                onClick={userSectionsSettingSubmit}
                className="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-xl px-6 py-3 shadow-md shadow-blue-100 flex items-center gap-2 transition-all transform hover:-translate-y-0.5"
              >
                <FaSave className="text-sm" />
                <span>حفظ الاعدادات</span>
              </button>
            </div>
          </>
        )}
        {activeTab === "warehouse" && (
          <div className="flex flex-col gap-6 animate-fadeIn">
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
              <div className="flex items-center gap-2 mb-4 text-slate-800 font-bold border-b border-slate-100 pb-3">
                <FaWarehouse className="text-blue-600" />
                <h2>تعيين المخزن الافتراضي</h2>
              </div>
              <WarehouseSettings
                WarehouseName={WarehouseName}
                setWarehouseName={setWarehouseName}
                //setDefaultWarehouseName={setDefaultWarehouseName}
                viewablePermissions={currentViewablePermissions}
                setViewablePermissions={(callback) => {
                  setScreenSettings((prev) => ({
                    ...prev,
                    viewablePermissions:
                      typeof callback === "function"
                        ? callback(prev.viewablePermissions)
                        : callback,
                  }));
                }}
                isUserMode={true}
              />
              <div className="w-full flex justify-between items-center border-t border-slate-100 pt-4 mt-2">
                <button
                  className=" bg-gray-500 hover:bg-gray-400 text-white rounded px-7 py-1.5"
                  onClick={handleUserWarehouseCancel}
                >
                  {" "}
                  الغاء
                </button>
                <button
                  onClick={warehouseSettingSubmit}
                  className="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-xl px-6 py-3 shadow-md shadow-blue-100 flex items-center gap-2 transition-all transform hover:-translate-y-0.5"
                >
                  <FaSave className="text-sm" />
                  <span>حفظ الاعدادات</span>
                </button>
              </div>
            </div>
          </div>
        )}

        {activeTab === "printer" && (
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 animate-fadeIn">
            <div className="flex items-center gap-2 mb-5 text-slate-800 font-bold border-b border-slate-100 pb-3">
              <FaPrint className="text-blue-600" />
              <h2>إعدادات ربط الطابعات والأجهزة بالسيستم</h2>
            </div>
            <PrinterSettingsPage
              printerName={userPrinterName}
              setPrinterName={setUserPrinterName}
              saveNoPrintAuth={saveNoPrintAuth}
              setSaveNoPrintAuth={setSaveNoPrintAuth}
              sectionName={sectionName}
              setSectionName={setSectionName}
              sectionId={sectionId}
              setSectionId={setSectionId}
              sectionPrinterName={sectionPrinterName}
              setSectionPrinterName={setSectionPrinterName}
              barcodePrinterName={barcodePrinterName}
              setBarcodePrinterName={setBarcodePrinterName}
              reportPrinterName={reportPrinterName}
              setReportPrinterName={setReportPrinterName}
              receiptPrinterName={receiptPrinterName}
              receiptModelName={receiptModelName}
              receiptNum={receiptNum}
              invoicePrinterName={invoicePrinterName}
              invoiceModelName={invoiceModelName}
              invoiceNum={invoiceNum}
              setReceiptPrinterName={setReceiptPrinterName}
              setReceiptModelName={setReceiptModelName}
              setReceiptNum={setReceiptNum}
              setInvoicePrinterName={setInvoicePrinterName}
              setInvoiceModelName={setInvoiceModelName}
              setInvoiceNum={setInvoiceNum}
              receiptPrintAuth={receiptPrintAuth}
              invoicePrintAuth={invoicePrintAuth}
              setReceiptPrintAuth={setReceiptPrintAuth}
              setInvoicePrintAuth={setInvoicePrintAuth}
              viewSectionsComponent={true}
            />
            <div className="w-full flex justify-between items-center border-t border-slate-100 pt-4 mt-2">
              <button
                className=" bg-gray-500 hover:bg-gray-400 text-white rounded px-7 py-1.5"
                onClick={printerSettingCancel}
              >
                {" "}
                الغاء
              </button>
              <button
                onClick={printerSettingSubmit}
                className="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-xl px-6 py-3 shadow-md shadow-blue-100 flex items-center gap-2 transition-all transform hover:-translate-y-0.5"
              >
                <FaSave className="text-sm" />
                <span>حفظ الاعدادات</span>
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
