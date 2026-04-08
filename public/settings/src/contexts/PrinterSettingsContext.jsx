import { createContext, useContext } from "react";
import usePrinterSettingsPreferenceLogic from "../hooks/settings/usePrinterSettingsPreferenceLogic";
const PrinterSettingsPreferenceContext = createContext(null);

export const PrinterSettingsPreferenceProvider = ({ children }) => {
  const printerSettingsPreferenceLogic = usePrinterSettingsPreferenceLogic();

  return (
    <PrinterSettingsPreferenceContext.Provider
      value={printerSettingsPreferenceLogic}
    >
      {children}
    </PrinterSettingsPreferenceContext.Provider>
  );
};

export const usePrinterSettingsPreference = () => {
  const context = useContext(PrinterSettingsPreferenceContext);
  if (!context) {
    throw new Error(
      "usePrinterSettingsPreferenceContext must be used inside usePrinterSettingsPreferenceProvider",
    );
  }
  return context;
};
