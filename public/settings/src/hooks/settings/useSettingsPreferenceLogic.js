import { useState } from "react";

export default function useSettingsPreferenceLogic() {
  const [defaultInvoiceType, setDefaultInvoiceType] = useState("تيك أواى");
  const [allowedInvoiceType, setAllowedInvoiceType] = useState(["تيك أواى"]);
  const [defaultPaymentMethod, setDefaultPaymentMethod] = useState("كاش");
  const [allowedPaymentMethods, setAllowedPaymentMethods] = useState(["كاش"]);
  const [defaultWarehouseName, setDefaultWarehouseName] = useState("مخزن 1");
  const [applyTax, setApplyTax] = useState(false);
  const [taxValue, setTaxValue] = useState(0.0);
  const [taxType, setTaxType] = useState("%");

  return {
    defaultInvoiceType,
    allowedInvoiceType,
    defaultPaymentMethod,
    allowedPaymentMethods,
    defaultWarehouseName,
    applyTax,
    taxValue,
    taxType,

    setDefaultInvoiceType,
    setAllowedInvoiceType,
    setDefaultPaymentMethod,
    setAllowedPaymentMethods,
    setDefaultWarehouseName,
    setApplyTax,
    setTaxValue,
    setTaxType,
  };
}
