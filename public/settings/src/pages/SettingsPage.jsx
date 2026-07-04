import InvoiceType from "../components/settings/general/InvoiceTypeSettings";
import PaymentSettings from "../components/settings/general/PaymentSettings";
import TaxSettings from "../components/settings/general/TaxSettings";
import WarehouseSettings from "../components/settings/screens/WarehouseSettings";
import { useInvoiceSettings } from "../contexts/InvoiceSettingsContext";
import { useSettingsPreference } from "../contexts/SettingsPreferenceContext";
import { useEffect, useState } from "react";
import notify from "../hooks/Notification";
import { useDispatch, useSelector } from "react-redux";
import {
  fetchConfigs,
  fetchInvoicePrintersConfig,
  fetchSectionsPrintersConfig,
  fetchWarehouseNames,
  sendConfigs,
  sendInvoicePrintersConfig,
  sendInvoiceWarehouseName,
  sendSectionsPrintersConfig,
  sendUserWarehouseConfig,
} from "../store/reducers/settingSlice";
import { fetchAdmin } from "../store/reducers/adminSlice";
import SectionsPrinterSettings from "../components/settings/printer/SectionsPrinterSettings";
import qz from "qz-tray";
import { getPrinters } from "../services/qzService";
import { FaPrint, FaWarehouse } from "react-icons/fa";
import { FaSection } from "react-icons/fa6";
import { FaFileInvoice } from "react-icons/fa";
import { BiSolidCategory } from "react-icons/bi";
import InvoicePrinterSettings from "../components/settings/printer/InvoicePrinterSettings";
import { usePrinterSettingsPreference } from "../contexts/PrinterSettingsContext";
import PrinterSettingsPage from "./PrinterSettingsPage";
import { useScreensPermissions } from "../contexts/ScreensPermissionsContext";
import InvoiceTypeSettings from "../components/settings/general/InvoiceTypeSettings";
import { useScreenSettingsPreference } from "../contexts/ScreenSettingsPreferenceContext";
import useScreenSettingsPreferenceLogic from "../hooks/settings/useScreenSettingsPreferenceLogic";

export default function SettingsPage() {
  const dispatch = useDispatch();
  const admin = useSelector((state) => state?.admin?.admin);
  const configs = useSelector((state) => state?.setting?.configs);
  const mainWarehouse = useSelector((state) => state?.setting?.mainWarehouse);
  const invoicePrintersConfig = useSelector(
    (state) => state?.setting?.invoicePrintersConfig,
  );
  const warehouseNames = useSelector((state) => state?.setting?.warehouseNames);
  const sectionsPrintersConfig = useSelector(
    (state) => state?.setting?.sectionsPrintersConfig,
  );

  const [availablePrinters, setAvailablePrinters] = useState([]);
  const [viewSectionsComponent, setViewSectionsComponent] = useState(true);
  const [activeTab, setActiveTab] = useState("invoice");

  const {
    defaultInvoiceType,
    allowedInvoiceType,
    defaultPaymentMethod,
    allowedPaymentMethods,
    defaultWarehouseName,
    applyTax,
    taxValue,
    taxType,
    sectionName,
    sectionId,
    sectionPrinterName,
    setSectionName,
    setSectionId,
    setSectionPrinterName,
    setDefaultInvoiceType,
    setAllowedInvoiceType,
    setDefaultPaymentMethod,
    setAllowedPaymentMethods,
    setApplyTax,
    setTaxValue,
    setTaxType,
    setDefaultWarehouseName,
    viewablePermissions,
    setViewablePermissions,
    sectionRows,
    setSectionRows,
  } = useSettingsPreference();

  const {WarehouseName, setWarehouseName} = useScreenSettingsPreferenceLogic()
    const { screenSettings, setScreenSettings } = useScreensPermissions();


  const invoiceMappingReversed = {
    take_away: "تيك أواى",
    delvery: "دليفرى",
    hall: "صالة",
  };
  const paymentMappingReversed = {
    cash: "كاش",
    credit_card: "بطاقة ائتمان",
    instapay: "انستا باى",
    wallet: "محفظة",
    remaining: "اجل",
  };
  const TaxMappingReversed = {
    "%": "%",
    pound: "ج.م",
  };

  useEffect(() => {
    dispatch(fetchAdmin());
    dispatch(fetchConfigs());
    dispatch(fetchWarehouseNames());
  }, [dispatch]);

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
    //  const saved = JSON.parse(localStorage.getItem("Invoice Settings"));
    if (!warehouseNames || warehouseNames.length === 0) return;
    if (warehouseNames && Object.keys(warehouseNames).length > 0) {
      const defaultwh = warehouseNames?.find((w) => w?.is_default === true);
      if (defaultwh?.id) {
        setDefaultWarehouseName(defaultwh?.id);
        setWarehouseName(defaultwh?.id)
        setScreenSettings((prev)=>({
          ...prev,
          warehouseId: defaultwh?.id,
          WarehouseName: defaultwh?.name
        }))
      }
    }
  }, [warehouseNames]);

  const [isInvoiceSettingsOpen, setIsInvoiceSettingsOpen] = useState(false);

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
  const { updateInvoiceSettings, updateUserSettings } = useInvoiceSettings();


  const {
    printerName,
    saveNoPrintAuth,
    barcodePrinterName,
    reportPrinterName,
    setPrinterName,
    setSaveNoPrintAuth,
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
  } = usePrinterSettingsPreference();

  const mappedAllowedInvoiceType = allowedInvoiceType.map((type) => {
    return invoiceMapping[type];
  });
  const mappedAllowedPaymentMethods = allowedPaymentMethods.map((type) => {
    return paymentMapping[type];
  });
  const handleSubmit = async () => {
    if (
      !allowedInvoiceType.includes(defaultInvoiceType) ||
      !allowedPaymentMethods.includes(defaultPaymentMethod)
    ) {
      notify("يحب أن تكون القيم الأفتراضية مسموحة", "error");
      return;
    }
    const payload = {
      type: "system",
      defaultInvoiceType: invoiceMapping[defaultInvoiceType],
      allowedInvoiceType: mappedAllowedInvoiceType,
      defaultPaymentMethod: paymentMapping[defaultPaymentMethod],
      allowedPaymentMethods: mappedAllowedPaymentMethods,
      defaultWarehouseName: defaultWarehouseName,
      applyTax: applyTax === false ? 0 : 1,
      taxValue: taxValue,
      taxTypes: TaxMapping[taxType],
      user_id: admin?.id,
    };
    try {
      await dispatch(sendConfigs({ invoiceSettings: payload })).unwrap();
      await dispatch(fetchConfigs());
      updateInvoiceSettings(payload);
      notify("تم حفظ الاعدادات بنجاح", "success");
    } catch (err) {
      notify("حدثت مشكلة برجاء المحاولة مرة أخرى", "error");
    }
  };
  const handleInvoiceCancel = () => {
    if (configs.defaultInvoiceType)
      setDefaultInvoiceType(
        invoiceMappingReversed[configs.defaultInvoiceType] || "تيك أواى",
      );
    if (configs.allowedInvoiceTypes)
      setAllowedInvoiceType(
        configs.allowedInvoiceTypes.map(
          (type) => invoiceMappingReversed[type] || type,
        ) || ["تيك أواى"],
      );
    if (configs.defaultPaymentMethod)
      setDefaultPaymentMethod(
        paymentMappingReversed[configs.defaultPaymentMethod] || "كاش",
      );
    if (configs.allowedPaymentMethods)
      setAllowedPaymentMethods(
        configs.allowedPaymentMethods.map(
          (type) => paymentMappingReversed[type] || type,
        ) || "كاش",
      );
    //if (mainWarehouse) setDefaultWarehouseName(mainWarehouse);
    if (configs.applyTax !== undefined) setApplyTax(configs.applyTax);
    if (configs.taxValue !== undefined) setTaxValue(configs.taxValue);
    if (configs.taxTypes)
      setTaxType(TaxMappingReversed[configs.taxTypes] || "%");
  };
  const handleSectionsPrintersSubmit = async () => {
    const payload = {
      data: [...sectionRows.filter((sec) => sec?.printer_name.length > 0)],
    };
    try {
      await dispatch(
        sendSectionsPrintersConfig({ sectionsPrintersConfig: payload }),
      ).unwrap();
      // updateInvoiceSettings(payload);
      notify("تم حفظ الاعدادات بنجاح", "success");
    } catch (err) {
      notify("حدثت مشكلة برجاء المحاولة مرة أخرى", "error");
    }
  };

  const handleSectionsPrintersCancel = () => {
    setSectionRows(
      sectionsPrintersConfig.map((item) => ({
        section_id: item.id,
        printer_name: item.printerSettings?.[0]?.printerName || "",
      })),
    );
  };
  const handleInvoiceWarehouseSubmit = async () => {
    const payload = {
      warehouse_id: defaultWarehouseName,
    };
    const formData = new FormData();
    formData.append("warehouse_id", defaultWarehouseName);
    try {
      await dispatch(
        sendInvoiceWarehouseName({ invoiceWarehouseName: formData }),
      ).unwrap();
      updateInvoiceSettings(payload);
      notify("تم حفظ الاعدادات بنجاح", "success");
    } catch (err) {
      notify("حدثت مشكلة برجاء المحاولة مرة أخرى", "error");
    }
  };
  const handleInvoiceWarehouseCancel = () => {
    const defaultwh = warehouseNames?.find((w) => w?.is_default === true);
    if (defaultwh?.id) {
      setDefaultWarehouseName(defaultwh?.id);
    }
  };
  const handleInvoicePrintersSubmit = async () => {
    const payload = {
      type: "system",
      cashierPrinterName: printerName,
      allowSaveWithoutPrint: saveNoPrintAuth === true ? 1 : 0,
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
        sendInvoicePrintersConfig({ invoicePrintersConfig: payload }),
      ).unwrap();
      // updateInvoiceSettings(payload);
      notify("تم حفظ الاعدادات بنجاح", "success");
    } catch (err) {
      notify("حدثت مشكلة برجاء المحاولة مرة أخرى", "error");
    }
  };
  const handleInvoicePrintersCancel = () => {
    if (
      !invoicePrintersConfig ||
      Object.keys(invoicePrintersConfig).length === 0
    )
      return;

    setPrinterName(invoicePrintersConfig?.cashierPrinterName);
    setSaveNoPrintAuth(
      invoicePrintersConfig?.allowSaveWithoutPrint === 1 ? true : false,
    );
    setBarcodePrinterName(invoicePrintersConfig?.barcodePrinterName);
    setReportPrinterName(invoicePrintersConfig?.reportPrinterName);
    const visibleReceipt = invoicePrintersConfig?.invicePrinters?.find(
      (conf) => conf?.permssionName === "Receipt Printing",
    );
    const visibleInvoice = invoicePrintersConfig?.invicePrinters?.find(
      (conf) => conf?.permssionName === "Invoice Printing",
    );

    setReceiptPrinterName(visibleReceipt?.printerName ?? "");
    setReceiptModelName(visibleReceipt?.formName ?? "");
    setReceiptNum(Number(visibleReceipt?.numOfCopies ?? 1));
    setReceiptPrintAuth(visibleReceipt?.isActive === 1 ? true : false);

    setInvoicePrinterName(visibleInvoice?.printerName ?? "");
    setInvoiceModelName(visibleInvoice?.formName ?? "");
    setInvoiceNum(Number(visibleInvoice?.numOfCopies ?? 1));
    setInvoicePrintAuth(visibleReceipt?.isActive === 1 ? true : false);
  };

  useEffect(() => {
    dispatch(fetchInvoicePrintersConfig());
  }, [dispatch]);

  useEffect(() => {
    const payload = {
      defaultInvoiceType: invoiceMapping[defaultInvoiceType],
      allowedInvoiceType: mappedAllowedInvoiceType,
      defaultPaymentMethod: paymentMapping[defaultPaymentMethod],
      allowedPaymentMethods: mappedAllowedPaymentMethods,
      defaultWarehouseName: defaultWarehouseName,
      applyTax: applyTax === false ? 0 : 1,
      taxValue: taxValue,
      taxType: TaxMapping[taxType],
      user_id: admin?.id,
      sectionId: sectionId,
      sectionPrinterName: sectionPrinterName,
      printerName: printerName,
      saveNoPrintAuth: saveNoPrintAuth,
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
      sectionRows: sectionRows,
      WarehouseName: defaultWarehouseName,
    };
    updateInvoiceSettings(payload);
    updateUserSettings(payload);
  }, [
    printerName,
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
    sectionRows,
    defaultWarehouseName,
  ]);

  useEffect(() => {
    if (
      !invoicePrintersConfig ||
      Object.keys(invoicePrintersConfig).length === 0
    )
      return;

    setPrinterName(invoicePrintersConfig?.cashierPrinterName);
    setSaveNoPrintAuth(
      invoicePrintersConfig?.allowSaveWithoutPrint === 1 ? true : false,
    );
    setBarcodePrinterName(invoicePrintersConfig?.barcodePrinterName);
    setReportPrinterName(invoicePrintersConfig?.reportPrinterName);
    //change
    const visibleReceipt = invoicePrintersConfig?.invicePrinters?.find(
      (conf) => conf?.permssionName === "Receipt Printing",
    );
    const visibleInvoice = invoicePrintersConfig?.invicePrinters?.find(
      (conf) => conf?.permssionName === "Invoice Printing",
    );

    setReceiptPrinterName(visibleReceipt?.printerName ?? "");
    setReceiptModelName(visibleReceipt?.formName ?? "");
    setReceiptNum(Number(visibleReceipt?.numOfCopies ?? 1));
    setReceiptPrintAuth(visibleReceipt?.isActive === 1 ? true : false);

    setInvoicePrinterName(visibleInvoice?.printerName ?? "");
    setInvoiceModelName(visibleInvoice?.formName ?? "");
    setInvoiceNum(Number(visibleInvoice?.numOfCopies ?? 1));
    setInvoicePrintAuth(visibleReceipt?.isActive === 1 ? true : false);
  }, [invoicePrintersConfig]);

  return (
    <div className="inset-0 z-50 bg-slate-50 w-full min-h-screen flex flex-col mx-auto lg:flex-row p-2 gap-x-10 pt-7">
      {/* Right Section (General Settings) */}
      <div className="w-full md:w-[90%] lg:w-[60%] mx-auto  flex flex-col gap-y-3">
        <h1 className="text-center font-bold text-2xl pb-3">
          الاعدادات العامة
        </h1>
        <div className="w-full mx-auto mb-6 bg-slate-200/60 p-1 rounded-xl flex gap-1 border border-slate-200">
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
            <span>اعدادات المخازن </span>
          </button>
          <button
            onClick={() => setActiveTab("printers")}
            className={`flex-1 flex items-center justify-center gap-2 py-2.5 text-xs font-bold rounded-lg transition-all ${
              activeTab === "printers"
                ? "bg-white text-blue-600 shadow-xs border border-slate-100"
                : "text-slate-600 hover:text-slate-900 hover:bg-white/40"
            }`}
          >
            <FaPrint className="text-sm" />
            <span>اعدادات طابعات الفاتورة</span>
          </button>
        </div>

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
            <div className="w-full flex justify-between mt-10 md:mt-5 lg:mt-4 px-5">
              <button
                className=" bg-gray-500 hover:bg-gray-400 text-white rounded px-7 py-1.5"
                onClick={handleInvoiceCancel}
              >
                {" "}
                الغاء
              </button>
              <button
                className=" bg-blue-700 hover:bg-blue-500 text-white rounded px-2 py-1.5"
                onClick={() => {
                  handleSubmit();
                }}
              >
                {" "}
                حفظ الاعدادات
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
            />
            <div className="w-full flex justify-between mt-10 md:mt-5 lg:mt-4 px-5">
              <button
                className=" bg-gray-500 hover:bg-gray-400 text-white rounded px-7 py-1.5"
                onClick={handleSectionsPrintersCancel}
              >
                {" "}
                الغاء
              </button>
              <button
                className=" bg-blue-700 hover:bg-blue-500 text-white rounded px-2 py-1.5"
                onClick={handleSectionsPrintersSubmit}
              >
                {" "}
                حفظ الاعدادات
              </button>
            </div>
          </>
        )}
        {activeTab === "warehouse" && (
          <>
            <div className="flex flex-col gap-6 animate-fadeIn">
              <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div className="flex items-center gap-2 mb-4 text-slate-800 font-bold border-b border-slate-100 pb-3">
                  <FaWarehouse className="text-blue-600" />
                  <h2>تعيين المخزن الافتراضي</h2>
                </div>
                <WarehouseSettings
                  WarehouseName={defaultWarehouseName}
                  setWarehouseName={setDefaultWarehouseName}
                  viewablePermissions={viewablePermissions}
                  setViewablePermissions={setViewablePermissions}
                  isUserMode={false}
                />
              </div>
            </div>
            <div className="w-full flex justify-between mt-10 md:mt-5 lg:mt-4 px-5">
              <button
                className=" bg-gray-500 hover:bg-gray-400 text-white rounded px-7 py-1.5"
                onClick={handleInvoiceWarehouseCancel}
              >
                {" "}
                الغاء
              </button>
              <button
                className=" bg-blue-700 hover:bg-blue-500 text-white rounded px-2 py-1.5"
                onClick={handleInvoiceWarehouseSubmit}
              >
                {" "}
                حفظ الاعدادات
              </button>
            </div>
          </>
        )}
        {activeTab === "printers" && (
          <>
            <div className="bg-white p-4 rounded-xl border border-slate-200/60 shadow-xs transition-all hover:border-blue-200">
              <PrinterSettingsPage
                printerName={printerName}
                setPrinterName={setPrinterName}
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
                viewSectionsComponent={false}
              />
            </div>
            <div className="w-full flex justify-between mt-10 md:mt-5 lg:mt-4 px-5">
              <button
                className=" bg-gray-500 hover:bg-gray-400 text-white rounded px-7 py-1.5"
                onClick={handleInvoicePrintersCancel}
              >
                {" "}
                الغاء
              </button>
              <button
                className=" bg-blue-700 hover:bg-blue-500 text-white rounded px-2 py-1.5"
                onClick={handleInvoicePrintersSubmit}
              >
                {" "}
                حفظ الاعدادات
              </button>
            </div>
          </>
        )}
      </div>
    </div>
  );
}
