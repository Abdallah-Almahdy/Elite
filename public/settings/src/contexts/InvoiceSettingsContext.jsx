import { createContext, useContext, useState } from "react";
import { useScreensPermissions } from "./ScreensPermissionsContext";

const InvoiceSettingsContext = createContext();

export const InvoiceSettingsProvider = ({ children }) => {
  //    const [screenSetting, setScreenSetting] = useState(() => {
  //   const saved = localStorage.getItem("Screens Settings");
  //   return saved ? JSON.parse(saved) : {};
  // });
  const { screenSettings, setScreenSettings } = useScreensPermissions();
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
  const updatePrinterSettings = (value) => {
    setInvoiceSetting(value);
    localStorage.setItem("Printer Settings", JSON.stringify(value));
  };
  // const updateScreenSettings = (value) => {
  //   setInvoiceSetting(value);
  //   localStorage.setItem("Screens Settings", JSON.stringify(value));
  // };

  const updateScreenSettings = (value) => {
    setScreenSettings((prev) => {
      const updated =
        typeof value === "function"
          ? value(prev)
          : {
              ...(prev || {}),
              ...value,
            };

      localStorage.setItem("Screens Settings", JSON.stringify(updated));

      return updated;
    });
  };

  return (
    // <UIPreferencesContext.Provider value={{ preference, updatePreference }}>
    <InvoiceSettingsContext.Provider
      value={{
        invoiceSetting,
        updateInvoiceSettings,
        updateUserSettings,
        updatePrinterSettings,
        updateScreenSettings,
      }}
    >
      {children}
    </InvoiceSettingsContext.Provider>
  );
};

export const useInvoiceSettings = () => useContext(InvoiceSettingsContext);
