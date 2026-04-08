import { useState } from "react";
import { useSelector, useDispatch } from "react-redux";

export default function usePrinterSettingsPreferenceLogic() {
  const users = useSelector((state) => state.user?.users);

  const [printerName, setPrinterName] = useState("");
  const [sectionName, setSectionName] = useState("");
  const [sectionPrinterName, setSectionPrinterName] = useState("");
  const [barcodePrinterName, setBarcodePrinterName] = useState("");
  const [reportPrinterName, setReportPrinterName] = useState("");
  const [receiptPrinterName, setReceiptPrinterName] = useState("");
  const [invoicePrinterName, setInvoicePrinterName] = useState("");
  const [receiptPrintAuth, setReceiptPrintAuth] = useState(false);
  const [invoicePrintAuth, setInvoicePrintAuth] = useState(false);
  const [receiptModelName, setReceiptModelName] = useState("");
  const [invoiceModelName, setInvoiceModelName] = useState("");
  const [receiptNum, setReceiptNum] = useState(1);
  const [invoiceNum, setInvoiceNum] = useState(1);
  const [saveNoPrintAuth, setSaveNoPrintAuth] = useState(false);
  const [userName, setUserName] = useState("");
  const [userNameResult, setUserNameResult] = useState("");
  const [userNameShowResult, setUserNameShowResult] = useState(false);
  const [errors, setErrors] = useState({
    userName: "",
    printerName: "",
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
    printerName,
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
    saveNoPrintAuth,
    userName,
    errors,
    userNameResult,
    userNameShowResult,

    setPrinterName,
    setSectionName,
    setSectionPrinterName,
    setBarcodePrinterName,
    setReportPrinterName,
    setReceiptPrinterName,
    setInvoicePrinterName,
    setReceiptPrintAuth,
    setInvoicePrintAuth,
    setReceiptModelName,
    setInvoiceModelName,
    setReceiptNum,
    setInvoiceNum,
    setSaveNoPrintAuth,
    setUserName,
    setErrors,
    setUserNameResult,
    setUserNameShowResult,

    handleSearchUserName,
    handleSelectUser,
  };
}
