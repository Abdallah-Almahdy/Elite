import { createContext, useCallback, useContext, useState } from "react";
import { useScreensPermissions } from "./ScreensPermissionsContext";

const InvoiceSettingsContext = createContext();

export const InvoiceSettingsProvider = ({ children }) => {
  //    const [screenSetting, setScreenSetting] = useState(() => {
  //   const saved = localStorage.getItem("Screens Settings");
  //   return saved ? JSON.parse(saved) : {};
  // });
  const { screenSettings, setScreenSettings } = useScreensPermissions();
  const [invoiceSetting, setInvoiceSetting] = useState(
    sessionStorage.getItem("Invoice Settings") || {},
  );

  const updateInvoiceSettings = (value) => {
    if (value) {
      // setInvoiceSetting(value);
      sessionStorage.setItem("Invoice Settings", JSON.stringify(value));
    }
  };

  const updateUserSettings = (value) => {
    if (value) {
      // setInvoiceSetting(value);
      sessionStorage.setItem("User Settings", JSON.stringify(value));
    }
  };
  const updatePrinterSettings = (value) => {
    if (value) {
      // setInvoiceSetting(value);
      sessionStorage.setItem("Printer Settings", JSON.stringify(value));
    }
  };

  const updateScreenSettings = useCallback(
    (value) => {
      setScreenSettings((prev) => {
        const updated =
          typeof value === "function"
            ? value(prev)
            : {
                ...(prev || {}),
                ...value,
              };

        sessionStorage.setItem("Screens Settings", JSON.stringify(updated));
        return updated;
      });
    },
    [setScreenSettings],
  );

  return (
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
