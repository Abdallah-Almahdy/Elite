import { useState } from "react";

export default function useSettingsPreferenceLogic() {
 const [defaultInvoiceType, setDefaultInvoiceType] = useState("تيك أواى");
 const [allowedInvoiceType, setAllowedInvoiceType] = useState(["تيك أواى"]);
 const [defaultPaymentMethod, setDefaultPaymentMethod] = useState("كاش");
 const [applyTax, setApplyTax] = useState(false);
 const [taxValue, setTaxValue] = useState(0.00);
 const [taxType, setTaxType] = useState("%");

 return {
    defaultInvoiceType,
    allowedInvoiceType,
    defaultPaymentMethod,
    applyTax,
    taxValue,
    taxType,

    setDefaultInvoiceType,
    setAllowedInvoiceType,
    setDefaultPaymentMethod,
    setApplyTax,
    setTaxValue,
    setTaxType,
 }
}
