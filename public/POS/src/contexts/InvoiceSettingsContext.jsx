import { createContext, useContext, useState } from "react";

const InvoiceSettingsContext = createContext();

export const InvoiceSettingsProvider = ({ children }) => {
  const [invoiceSetting, setInvoiceSetting] = useState(
    localStorage.getItem("Invoice Settings") || {},
  );

  const updateInvoiceSettings = (value) => {
    setInvoiceSetting(value);
    localStorage.setItem("Invoice Settings", JSON.stringify(value));
  };

  const updateUserSettings = (value) => {
    setInvoiceSetting(value);
    localStorage.setItem("User Settings", JSON.stringify(value));
  };

  return (
    // <UIPreferencesContext.Provider value={{ preference, updatePreference }}>
    <InvoiceSettingsContext.Provider value={{ invoiceSetting, updateInvoiceSettings, updateUserSettings }}>
      {children}
    </InvoiceSettingsContext.Provider>
  );
};

export const useInvoiceSettings = () => useContext(InvoiceSettingsContext);
