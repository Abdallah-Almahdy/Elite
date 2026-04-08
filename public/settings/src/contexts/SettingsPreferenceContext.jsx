import { createContext, useContext } from "react";
import useSettingsPreferenceLogic from "../hooks/settings/useSettingsPreferenceLogic";
const SettingsPreferenceContext = createContext(null);

export const SettingsPreferenceProvider = ({ children }) => {
  const settingsPreferenceLogic = useSettingsPreferenceLogic();

  return (
    <SettingsPreferenceContext.Provider value={settingsPreferenceLogic}>
      {children}
    </SettingsPreferenceContext.Provider>
  );
};

export const useSettingsPreference = () => {
  const context = useContext(SettingsPreferenceContext);
  if (!context) {
    throw new Error(
      "useSettingsPreferenceContext must be used inside SettingsPreferenceProvider",
    );
  }
  return context;
};
