import { createContext, useContext } from "react";
import useUserSettingsPreferenceLogic from "../hooks/settings/useUserSettingsPreferenceLogic";
const UserSettingsPreferenceContext = createContext(null);

export const UserSettingsPreferenceProvider = ({ children }) => {
  const userSettingsPreferenceLogic = useUserSettingsPreferenceLogic();

  return (
    <UserSettingsPreferenceContext.Provider value={userSettingsPreferenceLogic}>
      {children}
    </UserSettingsPreferenceContext.Provider>
  );
};

export const useUserSettingsPreference = () => {
  const context = useContext(UserSettingsPreferenceContext);
  if (!context) {
    throw new Error(
      "useUserSettingsPreferenceContext must be used inside UserSettingsPreferenceProvider",
    );
  }
  return context;
};
