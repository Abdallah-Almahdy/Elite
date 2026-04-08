import React from "react";
import PrinterSettings from "../components/settings/printer/PrinterSettings";
import { useInvoiceSettings } from "../contexts/InvoiceSettingsContext";
import { useState, useEffect } from "react";
import notify from "../hooks/Notification";
import UserSettings from "../components/settings/user/UserSettings";
import SectionsPrinterSettings from "../components/settings/printer/SectionsPrinterSettings";
import { usePrinterSettingsPreference } from "../contexts/PrinterSettingsContext";
import { getPrinters } from "../services/qzService";
import BarcodePrinterSettings from "../components/settings/printer/BarcodePrinterSettings";
import ReportPrinterSettings from "../components/settings/printer/ReportPrinterSettings";
import InvoicePrinterSettings from "../components/settings/printer/InvoicePrinterSettings";
import qz from "qz-tray";
import { useDispatch, useSelector } from "react-redux";
import { sendConfigs } from "../store/reducers/settingSlice";
import { fetchAdmin } from "../store/reducers/adminSlice";

export default function PrinterSettingsPage() {
  const [isPrinterSettingsOpen, setIsPrinterSettingsOpen] = useState(false);
  const [availablePrinters, setAvailablePrinters] = useState([]);
    const admin = useSelector((state) => state?.admin?.admin);

  const dispatch = useDispatch();


  const { updatePrinterSettings } = useInvoiceSettings();
  const {
    userName,
    printerName,
    saveNoPrintAuth,
    errors,
    sectionName,
    sectionPrinterName,
    reportPrinterName,
    barcodePrinterName,
    receiptPrinterName,
    invoicePrinterName,
    receiptPrintAuth,
    invoicePrintAuth,
    receiptModelName,
    invoiceModelName,
    receiptNum,
    invoiceNum,
  } = usePrinterSettingsPreference();

  useEffect(() => {
      dispatch(fetchAdmin());
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

  const printerSettingSubmit = async () => {
    const payload = {
      userName: userName,
      printerName: printerName,
      saveNoPrintAuth: saveNoPrintAuth,
      sectionName: sectionName,
      sectionPrinterName: sectionPrinterName,
      barcodePrinterName: barcodePrinterName,
      reportPrinterName: reportPrinterName,
      errors: errors,
      receiptPrinterName: receiptPrinterName,
      invoicePrinterName: invoicePrinterName,
      receiptPrintAuth: receiptPrintAuth,
      invoicePrintAuth: invoicePrintAuth,
      receiptModelName: receiptModelName,
      invoiceModelName: invoiceModelName,
      receiptNum: receiptNum,
      invoiceNum: invoiceNum,
    };
    const printerNamePayload = {
      printerName: printerName,
      user_id: admin?.id,
    }
    try {
          await dispatch(sendConfigs({ invoiceSettings: printerNamePayload })).unwrap();
          updatePrinterSettings(payload);
          notify("تم حفظ الاعدادات بنجاح", "success");
        } catch (err) {
          notify("حدثت مشكلة برجاء المحاولة مرة أخرى", "error");
        }
  };
  return (
    <div className="inset-0 z-50 bg-gray-200 w-full min-h-screen flex flex-col mx-auto lg:flex-row p-2 gap-x-10 pt-7 lg:pt-3">
      <div className="w-full md:w-[90%] lg:w-[55%] flex flex-col mx-auto">
        <h1 className="text-center font-bold text-2xl pb-2 mt-6 lg:mt-0">
          اعدادات الطباعة
        </h1>
        <div className="bg-white flex flex-col rounded-lg shadow-lg">
          <UserSettings />
          <PrinterSettings availablePrinters={availablePrinters} />
          <SectionsPrinterSettings availablePrinters={availablePrinters} />
          <BarcodePrinterSettings availablePrinters={availablePrinters} />
          <ReportPrinterSettings availablePrinters={availablePrinters} />
          <InvoicePrinterSettings availablePrinters={availablePrinters} />
          {/* <h2 className=' pr-2 font-semibold text-xl pt-3'>صلاحيات المستخدم :</h2> */}
        </div>
        <div className="w-full flex justify-end mt-10 md:mt-5 lg:mt-7 pl-5">
          <button
            className=" bg-blue-700 hover:bg-blue-500 text-white rounded px-2 py-1.5"
            onClick={() => {
              printerSettingSubmit();
            }}
          >
            {" "}
            حفظ الاعدادات
          </button>
        </div>
      </div>
    </div>
  );
}
