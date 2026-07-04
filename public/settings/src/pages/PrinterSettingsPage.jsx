import React, { useState, useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import qz from "qz-tray";
import { useInvoiceSettings } from "../contexts/InvoiceSettingsContext";
import { usePrinterSettingsPreference } from "../contexts/PrinterSettingsContext";
import { getPrinters } from "../services/qzService";
import {
  fetchUserPrintersConfig,
  sendConfigs,
} from "../store/reducers/settingSlice";
import { fetchAdmin } from "../store/reducers/adminSlice";
import notify from "../hooks/Notification";

import UserSettings from "../components/settings/user/UserSettings";
import PrinterSettings from "../components/settings/printer/PrinterSettings";
import SectionsPrinterSettings from "../components/settings/printer/SectionsPrinterSettings";
import BarcodePrinterSettings from "../components/settings/printer/BarcodePrinterSettings";
import ReportPrinterSettings from "../components/settings/printer/ReportPrinterSettings";
import InvoicePrinterSettings from "../components/settings/printer/InvoicePrinterSettings";

import { FaSave, FaTools, FaPrint } from "react-icons/fa";
import { fetchClientsNames } from "../store/reducers/userSlice";

export default function PrinterSettingsPage({
  printerName,
  setPrinterName,
  saveNoPrintAuth,
  setSaveNoPrintAuth,
  sectionName,
  setSectionName,
  sectionId,
  setSectionId,
  sectionPrinterName,
  setSectionPrinterName,
  barcodePrinterName,
  setBarcodePrinterName,
  reportPrinterName,
  setReportPrinterName,
  receiptPrinterName,
  invoicePrinterName,
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
  receiptPrintAuth,
  invoicePrintAuth,
  viewSectionsComponent,
}) {
  const [availablePrinters, setAvailablePrinters] = useState([]);
  const admin = useSelector((state) => state?.admin?.admin);

  const dispatch = useDispatch();
  const { updatePrinterSettings } = useInvoiceSettings();

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

  return (
    <div className="w-full flex flex-col gap-6" dir="rtl">
      <div className="bg-slate-50/60 rounded-xl p-4 border border-slate-100 flex flex-col gap-4">
        <div className="flex items-center gap-2 text-slate-600 font-medium text-xs border-b border-slate-200/40 pb-2">
          <FaPrint className="text-slate-400" />
          <span>تخصيص مسارات الطابعات الفعالة في النظام</span>
        </div>

        <div className="flex flex-col gap-4">
          <div className="bg-white p-4 rounded-xl border border-slate-200/60 shadow-xs transition-all hover:border-blue-200">
            <PrinterSettings
              availablePrinters={availablePrinters}
              printerName={printerName}
              setPrinterName={setPrinterName}
              saveNoPrintAuth={saveNoPrintAuth}
              setSaveNoPrintAuth={setSaveNoPrintAuth}
            />
          </div>

          {/* {viewSectionsComponent && (
           <div className="bg-white p-4 rounded-xl border border-slate-200/60 shadow-xs transition-all hover:border-blue-200">
            <SectionsPrinterSettings availablePrinters={availablePrinters} sectionName={sectionName} setSectionName={setSectionName} sectionPrinterName={sectionPrinterName} setSectionPrinterName={setSectionPrinterName} sectionId={sectionId} setSectionId={setSectionId}/>
          </div>
         )} */}

          <div className="bg-white p-4 rounded-xl border border-slate-200/60 shadow-xs transition-all hover:border-blue-200">
            <BarcodePrinterSettings
              availablePrinters={availablePrinters}
              barcodePrinterName={barcodePrinterName}
              setBarcodePrinterName={setBarcodePrinterName}
            />
          </div>

          <div className="bg-white p-4 rounded-xl border border-slate-200/60 shadow-xs transition-all hover:border-blue-200">
            <ReportPrinterSettings
              availablePrinters={availablePrinters}
              reportPrinterName={reportPrinterName}
              setReportPrinterName={setReportPrinterName}
            />
          </div>

          <div className="bg-white p-4 rounded-xl border border-slate-200/60 shadow-xs transition-all hover:border-blue-200">
            <InvoicePrinterSettings
              availablePrinters={availablePrinters}
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
              setReceiptPrintAuth={setReceiptPrintAuth}
              setInvoicePrintAuth={setInvoicePrintAuth}
              invoicePrintAuth={invoicePrintAuth}
            />
          </div>
        </div>
      </div>
    </div>
  );
}
