import { useEffect, useRef, useState } from "react";
import { useSelector, useDispatch } from "react-redux";
import {
  fetchConfigs,
  fetchInvoicePrintersConfig,
  fetchUserConfigs,
  fetchUserPrintersConfig,
} from "../../store/reducers/settingSlice";

export default function useUserSettingsPreferenceLogic() {
  const users = useSelector((state) => state.user?.users);
  const invoicePrintersConfig = useSelector(
    (state) => state?.setting?.invoicePrintersConfig,
  );
  const userPrintersConfig = useSelector(
    (state) => state?.setting?.userPrintersConfig,
  );
  const configs = useSelector((state) => state?.setting?.configs);
  const userConfigs = useSelector((state) => state?.setting?.userConfigs);
  const dispatch = useDispatch();
  const isInitialMount = useRef(true);

  const savedData = JSON.parse(sessionStorage.getItem("User Settings"));
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
  const TaxMappingReversed = { "%": "%", pound: "ج.م" };

  const [printerName, setPrinterName] = useState("");
  const [priceChangeAuth, setPriceChangeAuth] = useState(false);
  const [discountAuth, setDiscountAuth] = useState(false);
  const [deleteProdAuth, setDeleteProdAuth] = useState(false);
  const [passwordReq, setPasswordReq] = useState(false);
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [methodChangeAuth, setMethodChangeAuth] = useState(false);
  const [invoiceTypeChangeAuth, setInvoiceTypeChangeAuth] = useState(false);
  const [saveNoPrintAuth, setSaveNoPrintAuth] = useState(false);

  const [userName, setUserName] = useState(savedData?.userName || "");
  const [userId, setUserId] = useState(savedData?.userId || "");
  const [userNameResult, setUserNameResult] = useState("");
  const [userNameShowResult, setUserNameShowResult] = useState(false);
  const [userPrinterName, setUserPrinterName] = useState(
    savedData?.userPrinterName || "",
  );
  const [sectionName, setSectionName] = useState(savedData?.sectionName || "");
  const [sectionId, setSectionId] = useState(savedData?.sectionId || "");
  const [sectionPrinterName, setSectionPrinterName] = useState(
    savedData?.sectionPrinterName || "",
  );
  const [barcodePrinterName, setBarcodePrinterName] = useState(
    savedData?.barcodePrinterName || "",
  );
  const [reportPrinterName, setReportPrinterName] = useState(
    savedData?.reportPrinterName || "",
  );
  const [receiptPrinterName, setReceiptPrinterName] = useState(
    savedData?.receiptPrinterName || "",
  );
  const [invoicePrinterName, setInvoicePrinterName] = useState(
    savedData?.invoicePrinterName || "",
  );
  const [receiptPrintAuth, setReceiptPrintAuth] = useState(
    savedData?.receiptPrintAuth || false,
  );
  const [invoicePrintAuth, setInvoicePrintAuth] = useState(
    savedData?.invoicePrintAuth || false,
  );
  const [receiptModelName, setReceiptModelName] = useState(
    savedData?.receiptModelName || "",
  );
  const [invoiceModelName, setInvoiceModelName] = useState(
    savedData?.invoiceModelName || "",
  );
  const [receiptNum, setReceiptNum] = useState(savedData?.receiptNum || 1);
  const [invoiceNum, setInvoiceNum] = useState(savedData?.invoiceNum || 1);
  const [defaultInvoiceType, setDefaultInvoiceType] = useState(
    invoiceMappingReversed[savedData?.defaultInvoiceType] || "تيك أواى",
  );
  const [allowedInvoiceType, setAllowedInvoiceType] = useState(
    savedData?.allowedInvoiceType || ["تيك أواى"],
  );
  const [defaultPaymentMethod, setDefaultPaymentMethod] = useState(
    savedData?.defaultPaymentMethod || "كاش",
  );
  const [allowedPaymentMethods, setAllowedPaymentMethods] = useState(
    savedData?.allowedPaymentMethods || ["كاش"],
  );
  const [applyTax, setApplyTax] = useState(savedData?.applyTax || false);
  const [taxValue, setTaxValue] = useState(savedData?.taxValue || 0.0);
  const [taxType, setTaxType] = useState(savedData?.taxType || "%");
  const [sectionRows, setSectionRows] = useState(savedData?.sectionRows|| [
    {
      section_id: "",
      printer_name: "",
    },
  ]);

  const [errors, setErrors] = useState({
    userName: "",
    printerName: "",
    password: "",
    confirmPassword: "",
  });

  const handleSearchUserName = (val) => {
    if (!val) return [];
    return (
      users?.filter((user) =>
        user?.name?.toLowerCase().includes(val.toLowerCase()),
      ) || []
    );
  };

  useEffect(() => {
    if(userId){
    dispatch(fetchUserConfigs({id: userId}));
    dispatch(fetchUserPrintersConfig({id: userId}));
    }
    dispatch(fetchInvoicePrintersConfig());
    dispatch(fetchConfigs());
  }, [dispatch, userId]);

  useEffect(() => {
    // if (!configs || Object.keys(configs).length === 0) return;
    if (!configs) return;
    const appliedConfig =
      userConfigs && Object.keys(userConfigs).length > 0
        ? userConfigs
        : configs;
        
    setDefaultInvoiceType(
      invoiceMappingReversed[appliedConfig?.defaultInvoiceType] || "تيك أواى",
    );

    setAllowedInvoiceType(
      appliedConfig?.allowedInvoiceTypes?.map(
        (type) => invoiceMappingReversed[type],
      ) || ["تيك أواى"],
    );

    setDefaultPaymentMethod(
      paymentMappingReversed[appliedConfig?.defaultPaymentMethod] || "كاش",
    );

    setAllowedPaymentMethods(
      appliedConfig?.allowedPaymentMethods?.map(
        (method) => paymentMappingReversed[method],
      ) || ["كاش"],
    );

    setApplyTax(appliedConfig?.applyTax || false);
    setTaxValue(appliedConfig?.taxValue || 0);
    setTaxType(TaxMappingReversed[appliedConfig?.taxType] || "%");
  }, [configs, userConfigs]);

  useEffect(() => {
    // if (
    //   !invoicePrintersConfig ||
    //   Object.keys(invoicePrintersConfig).length === 0
    // )
    if (
      !invoicePrintersConfig 
      
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
  }, [invoicePrintersConfig, userPrintersConfig, users]);

  const handleSelectUser = (user) => {
    setUserName(user?.name);
    setUserNameResult([]);
  };

  const handleInvoiceUserCancel = () => {
    if (userConfigs.defaultInvoiceType)
      setDefaultInvoiceType(
        invoiceMappingReversed[userConfigs.defaultInvoiceType] || "تيك أواى",
      );
    if (userConfigs.allowedInvoiceTypes)
      setAllowedInvoiceType(
        userConfigs.allowedInvoiceTypes.map(
          (type) => invoiceMappingReversed[type] || type,
        ) || ["تيك أواى"],
      );
    if (userConfigs.defaultPaymentMethod)
      setDefaultPaymentMethod(
        paymentMappingReversed[userConfigs.defaultPaymentMethod] || "كاش",
      );
    if (userConfigs.allowedPaymentMethods)
      setAllowedPaymentMethods(
        userConfigs.allowedPaymentMethods.map(
          (type) => paymentMappingReversed[type] || type,
        ) || "كاش",
      );
    if (userConfigs.applyTax !== undefined) setApplyTax(userConfigs.applyTax);
    if (userConfigs.taxValue !== undefined) setTaxValue(userConfigs.taxValue);
    if (userConfigs.taxTypes)
      setTaxType(TaxMappingReversed[userConfigs.taxTypes] || "%");
  };

  return {
    printerName,
    priceChangeAuth,
    discountAuth,
    deleteProdAuth,
    passwordReq,
    password,
    confirmPassword,
    methodChangeAuth,
    invoiceTypeChangeAuth,
    userName,
    userId,
    errors,
    userNameResult,
    userNameShowResult,
    userPrinterName,
    saveNoPrintAuth,
    sectionName,
    sectionId,
    sectionPrinterName,
    barcodePrinterName,
    reportPrinterName,
    receiptPrinterName,
    receiptModelName,
    receiptPrintAuth,
    receiptNum,
    invoicePrinterName,
    invoiceModelName,
    invoicePrintAuth,
    invoiceNum,
    defaultInvoiceType,
    allowedInvoiceType,
    defaultPaymentMethod,
    allowedPaymentMethods,
    applyTax,
    taxType,
    taxValue,
    sectionRows,

    setPrinterName,
    setPriceChangeAuth,
    setDiscountAuth,
    setDeleteProdAuth,
    setPasswordReq,
    setPassword,
    setConfirmPassword,
    setMethodChangeAuth,
    setInvoiceTypeChangeAuth,
    setUserName,
    setUserId,
    setErrors,
    setUserNameResult,
    setUserNameShowResult,
    setUserPrinterName,
    setSaveNoPrintAuth,
    setSectionName,
    setSectionId,
    setSectionPrinterName,
    setBarcodePrinterName,
    setReportPrinterName,
    setReceiptPrinterName,
    setReceiptModelName,
    setReceiptPrintAuth,
    setReceiptNum,
    setInvoicePrinterName,
    setInvoiceModelName,
    setInvoicePrintAuth,
    setInvoiceNum,
    setDefaultInvoiceType,
    setAllowedInvoiceType,
    setDefaultPaymentMethod,
    setAllowedPaymentMethods,
    setApplyTax,
    setTaxType,
    setTaxValue,
    setSectionRows,

    handleSearchUserName,
    handleSelectUser,
    handleInvoiceUserCancel,
  };
}
