import { createContext, useContext } from "react";
import useScreenSettingsPreferenceLogic from "../hooks/settings/useScreenSettingsPreferenceLogic";
const ScreenSettingsPreferenceContext = createContext(null);

export const ScreenSettingsPreferenceProvider = ({ children }) => {
  const screenSettingsPreferenceLogic = useScreenSettingsPreferenceLogic();

  return (
    <ScreenSettingsPreferenceContext.Provider
      value={screenSettingsPreferenceLogic}
    >
      {children}
    </ScreenSettingsPreferenceContext.Provider>
  );
};

export const useScreenSettingsPreference = () => {
  const context = useContext(ScreenSettingsPreferenceContext);
  if (!context) {
    throw new Error(
      "useScreenSettingsPreferenceContext must be used inside ScreenSettingsPreferenceProvider",
    );
  }
  return context;
};
