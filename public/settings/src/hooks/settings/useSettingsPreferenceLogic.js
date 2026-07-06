import { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { fetchConfigs } from "../../store/reducers/settingSlice";

const STORAGE_KEY = "System Preference Settings";

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

export default function useSettingsPreferenceLogic() {
  const configs = useSelector((state) => state?.setting?.configs);
  const mainWarehouse = useSelector((state) => state?.setting?.mainWarehouse);
  const dispatch = useDispatch();

  // useEffect(() => {
  //   dispatch(fetchConfigs());
  // }, [dispatch]);

  const savedData = JSON.parse(sessionStorage.getItem("Invoice Settings"));

  const [defaultInvoiceType, setDefaultInvoiceType] = useState(
    invoiceMappingReversed[savedData?.defaultInvoiceType] || "تيك أواى",
  );
  const [allowedInvoiceType, setAllowedInvoiceType] = useState(
    savedData?.allowedInvoiceType?.map(
      (type) => invoiceMappingReversed[type] || type,
    ) || ["تيك أواى"],
  );
  const [defaultPaymentMethod, setDefaultPaymentMethod] = useState(
    paymentMappingReversed[savedData?.defaultPaymentMethod] || "كاش",
  );
  const [allowedPaymentMethods, setAllowedPaymentMethods] = useState(
    savedData?.allowedPaymentMethods?.map(
      (type) => paymentMappingReversed[type],
    ) || ["كاش"],
  );
  const [defaultWarehouseName, setDefaultWarehouseName] = useState(
    savedData?.WarehouseName || mainWarehouse,
  );
  const [applyTax, setApplyTax] = useState(savedData?.applyTax || false);
  const [taxValue, setTaxValue] = useState(savedData?.taxValue || 0.0);
  const [taxType, setTaxType] = useState(
    TaxMappingReversed[savedData?.taxTypes] || "%",
  );
  const [sectionName, setSectionName] = useState(savedData?.sectionName || "");
  const [sectionId, setSectionId] = useState(savedData?.sectionId || "");
  const [sectionPrinterName, setSectionPrinterName] = useState(
    savedData?.sectionPrinterName || "",
  );
  // const [allowedSectionsNames,setAllowerdSectionsNames] = useState(savedData?.allowedSectionsNames || []);
  const [viewablePermissions, setViewablePermissions] = useState(
    savedData?.viewablePermissions || [],
  );
  const [sectionRows, setSectionRows] = useState(
    savedData?.sectionRows || [
      {
        section_id: "",
        printer_name: "",
      },
    ],
  );

  //     const [allowedWareHouseName, setAllowedWareHouseName] = useState(() => {
  //   const saved = JSON.parse(localStorage.getItem("Invoice Settings"));
  //   if (saved && saved.allowedWareHouseName && saved.allowedWareHouseName.length > 0) {
  //     return saved.allowedWareHouseName;
  //   }
  //   return [{ id: 1, name: "مخزن 1" }];
  // });

  useEffect(() => {
    if (!configs || configs.length === 0) return;
    if (configs && Object.keys(configs).length > 0) {
      // const savedData = localStorage.getItem(STORAGE_KEY);
      // const parsedSaved = savedData ? JSON.parse(savedData) : null;

      // if (!savedData) {
      if (configs.defaultInvoiceType)
        setDefaultInvoiceType(
          invoiceMappingReversed[configs?.defaultInvoiceType] || "تيك أواى",
        );
      if (configs.allowedInvoiceTypes)
        setAllowedInvoiceType(
          configs?.allowedInvoiceTypes.map(
            (type) => invoiceMappingReversed[type] || type,
          ) || ["تيك أواى"],
        );
      if (configs.defaultPaymentMethod)
        setDefaultPaymentMethod(
          paymentMappingReversed[configs?.defaultPaymentMethod] || "كاش",
        );
      if (configs.allowedPaymentMethods)
        setAllowedPaymentMethods(
          configs?.allowedPaymentMethods.map(
            (type) => paymentMappingReversed[type] || type,
          ) || "كاش",
        );
      //if (mainWarehouse) setDefaultWarehouseName(mainWarehouse);
      if (configs.applyTax !== undefined) setApplyTax(configs?.applyTax);
      if (configs.taxValue !== undefined) setTaxValue(configs?.taxValue);
      if (configs.taxTypes)
        setTaxType(TaxMappingReversed[configs?.taxTypes] || "%");
      // } else {

      //   if (mainWarehouse && (!savedData?.defaultWarehouseName || savedData?.defaultWarehouseName === "undefined")) {
      //     setDefaultWarehouseName(mainWarehouse);
      //   }
      // }
    }
  }, [configs, mainWarehouse]);

  useEffect(() => {
    const statePayload = {
      defaultInvoiceType,
      allowedInvoiceType,
      defaultPaymentMethod,
      allowedPaymentMethods,
      defaultWarehouseName,
      applyTax,
      taxValue,
      taxType,
      viewablePermissions,
      sectionName,
      sectionId,
      sectionPrinterName,
      // allowedWareHouseName,
    };
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(statePayload));
  }, [
    defaultInvoiceType,
    allowedInvoiceType,
    defaultPaymentMethod,
    allowedPaymentMethods,
    defaultWarehouseName,
    applyTax,
    taxValue,
    taxType,
    viewablePermissions,
    mainWarehouse,
    sectionName,
    sectionId,
    sectionPrinterName,
  ]);

  return {
    defaultInvoiceType,
    allowedInvoiceType,
    defaultPaymentMethod,
    allowedPaymentMethods,
    defaultWarehouseName,
    applyTax,
    taxValue,
    taxType,
    viewablePermissions,
    sectionName,
    sectionId,
    sectionPrinterName,
    sectionRows,
    // allowedWareHouseName,

    setDefaultInvoiceType,
    setAllowedInvoiceType,
    setDefaultPaymentMethod,
    setAllowedPaymentMethods,
    setDefaultWarehouseName,
    setApplyTax,
    setTaxValue,
    setTaxType,
    setViewablePermissions,
    setSectionName,
    setSectionId,
    setSectionPrinterName,
    setSectionRows,
    // setAllowedWareHouseName,
  };
}
