import { useState } from "react";
import { useSelector, useDispatch } from "react-redux";

export default function usePrinterSettingsPreferenceLogic() {
  const users = useSelector((state) => state.user?.users);
    const savedData = JSON.parse(
    sessionStorage.getItem("Invoice Settings"),
  ) ?? {};

  const [printerName, setPrinterName] = useState(savedData?.printerName ||"");
  const [barcodePrinterName, setBarcodePrinterName] = useState(savedData?.barcodePrinterName ||"");
  const [reportPrinterName, setReportPrinterName] = useState(savedData?.reportPrinterName ||"");
  const [receiptPrinterName, setReceiptPrinterName] = useState(savedData?.receiptPrinterName ||"");
  const [invoicePrinterName, setInvoicePrinterName] = useState(savedData?.invoicePrinterName ||"");
  const [receiptPrintAuth, setReceiptPrintAuth] = useState(savedData?.receiptPrintAuth ||false);
  const [invoicePrintAuth, setInvoicePrintAuth] = useState(savedData?.invoicePrintAuth ||false);
  const [receiptModelName, setReceiptModelName] = useState(savedData?.receiptModelName ||"");
  const [invoiceModelName, setInvoiceModelName] = useState(savedData?.invoiceModelName ||"");
  const [receiptNum, setReceiptNum] = useState(savedData?.receiptNum ||1);
  const [invoiceNum, setInvoiceNum] = useState(savedData?.invoiceNum ||1);
  const [saveNoPrintAuth, setSaveNoPrintAuth] = useState(savedData?.saveNoPrintAuth ||false);
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
      if (user?.name?.toLowerCase().includes(val?.toLowerCase())) {
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
